<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        $links = Link::orderBy('position')->get();
        return view('admin.links.index', compact('links'));
    }

    public function create()
    {
        return view('admin.links.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'position' => 'integer',
        ]);

        Link::create($request->all());
        return redirect()->route('admin.links.index')->with('success', 'Đã tạo liên kết thành công.');
    }

    public function edit(Link $link)
    {
        return view('admin.links.edit', compact('link'));
    }

    public function update(Request $request, Link $link)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'position' => 'integer',
        ]);

        $link->update($request->all());
        return redirect()->route('admin.links.index')->with('success', 'Đã cập nhật liên kết thành công.');
    }

    public function destroy(Link $link)
    {
        $link->delete();
        return back()->with('success', 'Đã xóa liên kết thành công.');
    }
}
