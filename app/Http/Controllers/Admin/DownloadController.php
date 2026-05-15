<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookDownload;
use App\Models\Book;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Models\Category::all();
        
        // Statistics of most downloaded books
        $query = Book::with(['category', 'author'])->withCount('downloads');
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $downloadStats = $query->orderBy('downloads_count', 'desc')
            ->paginate(10);

        // Recent downloads list
        $recentDownloads = BookDownload::with(['user', 'book'])->latest('downloaded_at')->take(10)->get();

        return view('admin.downloads.index', compact('downloadStats', 'recentDownloads', 'categories'));
    }
}
