<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Book;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\Category::all();

        // Statistics of most favorited books
        $query = Book::with(['category', 'author'])->withCount('favoritedBy');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $favoriteStats = $query->orderBy('favorited_by_count', 'desc')
            ->paginate(10);

        return view('admin.favorites.index', compact('favoriteStats', 'categories'));
    }
}
