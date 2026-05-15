<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::where('user_id', Auth::id())->latest()->paginate(10);
        return view('user.books.index', compact('books'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all();
        $authors = \App\Models\Author::all();
        $publishers = \App\Models\Publisher::all();
        return view('user.books.create', compact('categories', 'authors', 'publishers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'thumbnail' => 'required|image|max:2048',
            'file_path' => 'required|mimes:pdf,epub,docx|max:20480',
            'price_points' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title) . '-' . uniqid();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending'; // User uploads require approval

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $data['file_path'] = $file->store('documents', 'public');
            
            // Note: Conversion and preview logic could be extracted to a Service or Job
            // For now, I'll assume Admin manages the conversion/preview or it's handled here similarly.
        }

        Book::create($data);

        return redirect()->route('user.books.index')->with('success', 'Tài liệu đã được đăng và đang chờ quản trị viên phê duyệt.');
    }
}
