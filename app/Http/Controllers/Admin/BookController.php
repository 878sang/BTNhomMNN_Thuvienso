<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['category', 'author', 'publisher', 'user'])->latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        $authors = \App\Models\Author::all();
        $publishers = \App\Models\Publisher::all();
        return view('admin.books.create', compact('categories', 'authors', 'publishers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'thumbnail' => 'required|image|max:2048',
            'file_path' => 'required|mimes:pdf,epub,docx|max:20480', // 20MB max
            'price_points' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = \Illuminate\Support\Str::slug($request->title) . '-' . uniqid();
        $data['user_id'] = \Illuminate\Support\Facades\Auth::guard('admin')->id(); // Admin is uploader
        $data['status'] = 'approved'; // Admin uploads are auto-approved

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $data['file_path'] = $file->store('documents', 'public');
            $extension = $file->getClientOriginalExtension();
            $fullPath = storage_path('app/public/' . $data['file_path']);
            $pdfPath = $fullPath;

            // If Word, convert to PDF first
            if (in_array($extension, ['doc', 'docx'])) {
                $pdfFileName = 'converted_' . pathinfo($data['file_path'], PATHINFO_FILENAME) . '.pdf';
                $pdfStoragePath = storage_path('app/public/documents/' . $pdfFileName);
                if ($this->convertWordToPdf($fullPath, $pdfStoragePath)) {
                    $data['pdf_version_path'] = 'documents/' . $pdfFileName;
                    $pdfPath = $pdfStoragePath;
                }
            }

            // Generate preview from PDF (either original or converted)
            if ($extension == 'pdf' || isset($data['pdf_version_path'])) {
                $previewFileName = 'preview_' . pathinfo($data['file_path'], PATHINFO_FILENAME) . '.pdf';
                $data['preview_path'] = $this->generatePreview($pdfPath, $previewFileName);
            }
        }

        Book::create($data);

        return redirect()->route('admin.books.index')->with('success', 'Tài liệu đã được đăng và phê duyệt thành công.');
    }

    public function edit(Book $book)
    {
        $categories = \App\Models\Category::all();
        $authors = \App\Models\Author::all();
        $publishers = \App\Models\Publisher::all();
        return view('admin.books.edit', compact('book', 'categories', 'authors', 'publishers'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'thumbnail' => 'nullable|image|max:2048',
            'file_path' => 'nullable|mimes:pdf,epub,docx|max:20480',
            'price_points' => 'required|integer|min:0',
        ]);

        $data = $request->all();

        if ($request->hasFile('thumbnail')) {
            if ($book->thumbnail) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($book->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('file_path')) {
            if ($book->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($book->file_path);
            }
            if ($book->preview_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($book->preview_path);
            }
            if ($book->pdf_version_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($book->pdf_version_path);
            }

            $file = $request->file('file_path');
            $data['file_path'] = $file->store('documents', 'public');
            $extension = $file->getClientOriginalExtension();
            $fullPath = storage_path('app/public/' . $data['file_path']);
            $pdfPath = $fullPath;

            // If Word, convert to PDF
            if (in_array($extension, ['doc', 'docx'])) {
                $pdfFileName = 'converted_' . pathinfo($data['file_path'], PATHINFO_FILENAME) . '.pdf';
                $pdfStoragePath = storage_path('app/public/documents/' . $pdfFileName);
                if ($this->convertWordToPdf($fullPath, $pdfStoragePath)) {
                    $data['pdf_version_path'] = 'documents/' . $pdfFileName;
                    $pdfPath = $pdfStoragePath;
                }
            }

            // Generate preview from PDF
            if ($extension == 'pdf' || isset($data['pdf_version_path'])) {
                $previewFileName = 'preview_' . pathinfo($data['file_path'], PATHINFO_FILENAME) . '.pdf';
                $data['preview_path'] = $this->generatePreview($pdfPath, $previewFileName);
            }
        }

        $book->update($data);

        return redirect()->route('admin.books.index')->with('success', 'Cập nhật tài liệu thành công.');
    }

    public function destroy(Book $book)
    {
        if ($book->thumbnail) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($book->thumbnail);
        }
        if ($book->file_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($book->file_path);
        }
        if ($book->preview_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($book->preview_path);
        }
        if ($book->pdf_version_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($book->pdf_version_path);
        }
        $book->delete();
        return redirect()->route('admin.books.index')->with('success', 'Đã xóa tài liệu thành công.');
    }

    public function approve(Book $book)
    {
        $book->update(['status' => 'approved']);
        return back()->with('success', 'Đã phê duyệt tài liệu.');
    }

    public function reject(Book $book)
    {
        $book->update(['status' => 'rejected']);
        return back()->with('success', 'Đã từ chối tài liệu.');
    }

    private function convertWordToPdf($wordFilePath, $outputPdfPath)
    {
        try {
            // Using dompdf as the renderer for PHPWord
            \PhpOffice\PhpWord\Settings::setPdfRendererName(\PhpOffice\PhpWord\Settings::PDF_RENDERER_DOMPDF);
            \PhpOffice\PhpWord\Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));

            $phpWord = \PhpOffice\PhpWord\IOFactory::load($wordFilePath);
            $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'PDF');
            $xmlWriter->save($outputPdfPath);
            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Word to PDF Conversion Failed: ' . $e->getMessage());
            return false;
        }
    }

    private function generatePreview($fullFilePath, $previewFileName)
    {
        try {
            $pdf = new \setasign\Fpdi\Fpdi();
            $pageCount = $pdf->setSourceFile($fullFilePath);
            $pagesToExtract = min($pageCount, 5);

            for ($n = 1; $n <= $pagesToExtract; $n++) {
                $tplIdx = $pdf->importPage($n);
                $size = $pdf->getTemplateSize($tplIdx);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tplIdx);
            }

            $storagePath = storage_path('app/public/previews/' . $previewFileName);
            if (!file_exists(dirname($storagePath))) {
                mkdir(dirname($storagePath), 0755, true);
            }
            $pdf->Output('F', $storagePath);
            return 'previews/' . $previewFileName;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PDF Preview Generation Failed: ' . $e->getMessage());
            return null;
        }
    }
}
