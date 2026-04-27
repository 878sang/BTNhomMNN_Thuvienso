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
        if (auth()->check()) {
            $hasPurchased = auth()->user()->purchasedBooks()->where('book_id', $book->id)->exists();
        }

        return view('books-detail', compact('book', 'hasPurchased'));
    }

    public function purchase(Book $book)
    {
        $user = auth()->user();
        if (!$user) return redirect()->route('login');

        if ($book->status !== 'approved') {
            return back()->with('error', 'This document is not available.');
        }

        if ($user->purchasedBooks()->where('book_id', $book->id)->exists()) {
            return back()->with('success', 'You already have access to this document.');
        }

        if ($user->points < $book->price_points) {
            return redirect()->route('payment.recharge')->with('error', 'You do not have enough points. Please recharge.');
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

            return back()->with('success', 'Document unlocked! Download link is now active.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }
}
