<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rating;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookRatingController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $user = Auth::user();
        $existingRating = Rating::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        // Lần đầu: Tạo đánh giá + bình luận
        if (!$existingRating) {
            $request->validate([
                'stars' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);

            Rating::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'stars' => $request->stars,
                'comment' => $request->comment,
            ]);

            // Nếu có bình luận thêm
            if ($request->has('additional_comment') && $request->additional_comment) {
                Comment::create([
                    'user_id' => $user->id,
                    'book_id' => $book->id,
                    'content' => $request->additional_comment,
                    'status' => 'pending',
                ]);
            }

            return back()->with('success', 'Cảm ơn bạn đã đánh giá và bình luận!');
        }

        // Những lần sau: Chỉ thêm bình luận
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'content' => $request->comment,
            'status' => 'approved',
        ]);

        return back()->with('success', 'Bình luận của bạn đã được gửi!');
    }
}
