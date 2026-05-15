<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function transactions()
    {
        $transactions = auth()->user()->transactions()->latest()->paginate(10);
        return view('user.transactions', compact('transactions'));
    }

    public function profile()
    {
        $user = auth()->user();
        $purchasedBooks = $user->purchasedBooks()->latest()->take(5)->get();
        return view('user.profile', compact('user', 'purchasedBooks'));
    }

    public function wishlist()
    {
        $favorites = auth()->user()->favorites()
            ->with(['book.author', 'book.category'])
            ->where('status', 'active')
            ->latest()
            ->paginate(12);
        return view('user.wishlist', compact('favorites'));
    }

    public function purchased()
    {
        $purchasedBooks = auth()->user()->purchasedBooks()
            ->with(['author', 'category'])
            ->latest('pivot_created_at')
            ->paginate(12);
        return view('user.purchased', compact('purchasedBooks'));
    }
}
