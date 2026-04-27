<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = \App\Models\Slider::where('status', true)->orderBy('order')->get();
        $categories = \App\Models\Category::withCount('books')->take(6)->get();
        $featuredBooks = \App\Models\Book::where('status', 'approved')->with(['author', 'category'])->latest()->take(8)->get();
        $stats = [
            'books' => \App\Models\Book::where('status', 'approved')->count(),
            'authors' => \App\Models\Author::count(),
            'users' => \App\Models\User::count(),
        ];

        return view('home', compact('sliders', 'categories', 'featuredBooks', 'stats'));
    }
}
