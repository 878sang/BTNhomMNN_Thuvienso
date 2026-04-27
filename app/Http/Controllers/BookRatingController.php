<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookRatingController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user purchased the book or it's free
        $hasPurchased = Auth::user()->purchasedBooks()->where('book_id', $book->id)->exists();
        if (!$hasPurchased && $book->price_points > 0) {
            return back()->with('error', 'Bạn cần sở hữu tài liệu này trước khi đánh giá.');
        }

        Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'book_id' => $book->id],
            ['stars' => $request->stars, 'comment' => $request->comment]
        );

        return back()->with('success', 'Cảm ơn bạn đã đánh giá tài liệu!');
    }
}
