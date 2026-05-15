<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    use \App\Traits\HandlesBookUploads;

    public function index(Request $request)
    {
        $query = Book::where('status', 'approved')->with(['author', 'category']);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $books = $query->latest()->paginate(12);
        $totalBooksCount = Book::where('status', 'approved')->count();
        $categories = \App\Models\Category::withCount(['books' => function($q) {
            $q->where('status', 'approved');
        }])->get();

        return view('books', compact('books', 'categories', 'totalBooksCount'));
    }

    public function show($slug)
    {
        $book = Book::where('slug', $slug)->where('status', 'approved')->with(['author', 'category', 'publisher', 'comments.user'])->firstOrFail();
        
        $book->increment('view_count');

        $hasPurchased = ($book->price_points == 0);
        $isFavorited = false;
        if (auth()->check()) {
            $hasPurchased = $hasPurchased || auth()->user()->purchasedBooks()->where('book_id', $book->id)->exists();
            $isFavorited = auth()->user()->favorites()->where('book_id', $book->id)->exists();
        }

        return view('books-detail', compact('book', 'hasPurchased', 'isFavorited'));
    }

    public function purchase(Book $book)
    {
        $user = auth()->user();
        if (!$user) return redirect()->route('login');

        if ($book->status !== 'approved') {
            return back()->with('error', 'Tài liệu này hiện không khả dụng.');
        }

        if ($user->purchasedBooks()->where('book_id', $book->id)->exists()) {
            return back()->with('success', 'Bạn đã mua tài liệu này rồi.');
        }

        if ($user->points < $book->price_points) {
            return redirect()->route('payment.recharge')->with('error', 'Bạn không đủ điểm. Vui lòng nạp thêm.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($user, $book) {
                $user->decrement('points', $book->price_points);
                \App\Models\PointsTransaction::create([
                    'user_id' => $user->id,
                    'amount' => 0,
                    'points' => $book->price_points,
                    'type' => 'download',
                    'status' => 'completed',
                    'reference_id' => 'PURCHASE-' . $book->id . '-' . time(),
                ]);
                $user->purchasedBooks()->attach($book->id, ['price_paid' => $book->price_points]);
            });

            return back()->with('success', 'Mở khóa tài liệu thành công! Bạn có thể tải về ngay bây giờ.');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    public function toggleFavorite(Book $book)
    {
        $user = auth()->user();
        $favorite = \App\Models\Favorite::where('user_id', $user->id)->where('book_id', $book->id)->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            \App\Models\Favorite::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
            ]);
            return response()->json(['status' => 'added']);
        }
    }

    public function download(Book $book)
    {
        $user = auth()->user();
        
        // 1. Kiểm tra quyền tải
        $canDownload = ($user && $user->role === 'admin') || ($user && $user->purchasedBooks()->where('book_id', $book->id)->exists());

        if (!$canDownload) {
            return back()->with('error', 'Bạn cần mua tài liệu này trước khi tải về.');
        }

        // 2. Xác định file cần tải (Gốc hay PDF đã chuyển đổi)
        $downloadPath = $book->file_path;
        if (request('format') === 'pdf' && $book->pdf_version_path) {
            $downloadPath = $book->pdf_version_path;
        }

        // 3. Ghi log lượt tải (nếu không phải Admin)
        if (!$user || $user->role !== 'admin') {
            $book->increment('download_count');
            \App\Models\BookDownload::create([
                'book_id' => $book->id,
                'user_id' => $user->id,
                'ip_address' => request()->ip(),
                'downloaded_at' => now(),
            ]);

            // Cộng điểm hoa hồng cho người đăng
            if ($book->user && $book->user_id !== $user->id && $book->user->role !== 'admin') {
                $commissionPercent = (int) (\App\Models\Setting::getVal('uploader_commission_percent') ?: 20);
                $pointsToAward = round($book->price_points * ($commissionPercent / 100));
                
                if ($pointsToAward > 0) {
                    $book->user->increment('points', $pointsToAward);
                    \App\Models\PointsTransaction::create([
                        'user_id' => $book->user_id,
                        'points' => $pointsToAward,
                        'type' => 'bonus',
                        'status' => 'completed',
                        'reference_id' => 'DOWNLOAD-COMMISSION-' . $book->id . '-' . $user->id,
                    ]);
                }
            }
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($downloadPath, $book->title . '.' . pathinfo($downloadPath, PATHINFO_EXTENSION));
    }

    public function previewPdf(Book $book)
    {
        $user = auth()->user();
        $hasPurchased = $user && ($user->purchasedBooks()->where('book_id', $book->id)->exists() || $user->role === 'admin');

        // 1. Nếu đã mua hoặc là Admin -> Cho xem bản đầy đủ
        if ($hasPurchased) {
            if ($book->pdf_version_path) {
                $path = storage_path('app/public/' . $book->pdf_version_path);
                if (file_exists($path)) return response()->file($path);
            }
            $filePath = storage_path('app/public/' . $book->file_path);
            if (!file_exists($filePath)) return abort(404, 'Tài liệu không tồn tại.');

            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if ($extension === 'pdf') return response()->file($filePath);
            
            // Nếu là Word mà chưa có bản PDF -> Chuyển đổi fallback
            return $this->handleFallbackConversion($book, $filePath);
        }

        // 2. Nếu CHƯA MUA -> Chỉ cho xem bản Preview (5 trang)
        if ($book->preview_path) {
            $previewPath = storage_path('app/public/' . $book->preview_path);
            if (file_exists($previewPath)) return response()->file($previewPath);
        }

        // Nếu chưa có file preview sẵn, thử tạo ngay lập tức
        return $this->handlePreviewGeneration($book);
    }

    private function handleFallbackConversion($book, $filePath)
    {
        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
            \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);
            \PhpOffice\PhpWord\Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
            $pdfWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
            
            ob_start();
            $pdfWriter->save("php://output");
            $pdfContent = ob_get_clean();

            return response($pdfContent, 200)->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            return abort(500, 'Lỗi chuyển đổi tài liệu.');
        }
    }

    private function handlePreviewGeneration($book)
    {
        $filePath = storage_path('app/public/' . $book->file_path);
        if (!file_exists($filePath)) return abort(404);

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $tempPdfPath = null;

        try {
            // Nếu là Word -> Chuyển sang PDF tạm thời
            if ($extension === 'docx') {
                $tempPdfPath = storage_path('app/public/documents/temp_' . $book->id . '.pdf');
                if (!$this->convertWordToPdf($filePath, $tempPdfPath)) throw new \Exception('Word conversion failed');
                $sourcePath = $tempPdfPath;
            } else if ($extension === 'pdf') {
                $sourcePath = $filePath;
            } else {
                return abort(403, 'Định dạng không hỗ trợ xem trước.');
            }

            // Tạo preview 5 trang
            $previewFileName = 'preview_' . $book->id . '_' . time() . '.pdf';
            $previewPath = $this->generatePreview($sourcePath, $previewFileName);

            if ($previewPath) {
                // Lưu vào database để lần sau không phải tạo lại
                $book->update(['preview_path' => $previewPath]);
                
                // Xóa file tạm nếu có
                if ($tempPdfPath && file_exists($tempPdfPath)) unlink($tempPdfPath);

                return response()->file(storage_path('app/public/' . $previewPath));
            }

            throw new \Exception('Preview generation failed');
        } catch (\Exception $e) {
            if ($tempPdfPath && file_exists($tempPdfPath)) unlink($tempPdfPath);
            return abort(500, 'Không thể tạo bản xem trước lúc này.');
        }
    }
}
