<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::latest()->paginate(10);
        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'bio']);
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('authors', 'public');
        }

        Author::create($data);

        return redirect()->route('admin.authors.index')->with('success', 'Đã thêm tác giả thành công.');
    }

    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }

    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'bio']);

        if ($request->hasFile('image')) {
            if ($author->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($author->image);
            }
            $data['image'] = $request->file('image')->store('authors', 'public');
        }

        $author->update($data);

        return redirect()->route('admin.authors.index')->with('success', 'Đã cập nhật tác giả thành công.');
    }

    public function destroy(Author $author)
    {
        if ($author->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($author->image);
        }
        $author->delete();
        return redirect()->route('admin.authors.index')->with('success', 'Đã xóa tác giả thành công.');
    }
}
