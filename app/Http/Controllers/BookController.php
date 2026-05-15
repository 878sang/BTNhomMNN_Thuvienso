<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::where('status', 'approved')->with(['author', 'category']);

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $books = $query->latest()->paginate(12);
        $categories = \App\Models\Category::withCount(['books' => function($q) {
            $q->where('status', 'approved');
        }])->get();

        return view('books', compact('books', 'categories'));
    }

    public function show($slug)
    {
        $book = Book::where('slug', $slug)->where('status', 'approved')->with(['author', 'category', 'publisher', 'comments.user'])->firstOrFail();
        
        $book->increment('view_count');

        $hasPurchased = false;
        $isFavorited = false;
        if (auth()->check()) {
            $hasPurchased = auth()->user()->purchasedBooks()->where('book_id', $book->id)->exists();
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
        
        // Admin can download anything
        if ($user && $user->role === 'admin') {
            return \Illuminate\Support\Facades\Storage::disk('public')->download($book->file_path, $book->title . '.' . pathinfo($book->file_path, PATHINFO_EXTENSION));
        }

        if (!$user || !$user->purchasedBooks()->where('book_id', $book->id)->exists()) {
            return back()->with('error', 'Bạn cần mua tài liệu này trước khi tải về.');
        }

        // Increment download count and log interaction
        $book->increment('download_count');
        \App\Models\BookDownload::create([
            'book_id' => $book->id,
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'downloaded_at' => now(),
        ]);

        // Award points to uploader if someone else downloads it
        if ($book->user && $book->user_id !== $user->id && $book->user->role !== 'admin') {
            $commissionPercent = (int) (\App\Models\Setting::getVal('uploader_commission_percent') ?: 20);
            $pointsToAward = round($book->price_points * ($commissionPercent / 100));
            
            if ($pointsToAward > 0) {
                $book->user->increment('points', $pointsToAward);
                \App\Models\PointsTransaction::create([
                    'user_id' => $book->user_id,
                    'amount' => 0,
                    'points' => $pointsToAward,
                    'type' => 'bonus',
                    'status' => 'completed',
                    'reference_id' => 'DOWNLOAD-COMMISSION-' . $book->id . '-' . $user->id,
                ]);
            }
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($book->file_path, $book->title . '.' . pathinfo($book->file_path, PATHINFO_EXTENSION));
    }
}
