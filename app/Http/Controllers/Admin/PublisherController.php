<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Publisher;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::latest()->paginate(10);
        return view('admin.publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Publisher::create($request->all());

        return redirect()->route('admin.publishers.index')->with('success', 'Đã thêm nhà xuất bản thành công.');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $publisher->update($request->all());

        return redirect()->route('admin.publishers.index')->with('success', 'Đã cập nhật nhà xuất bản thành công.');
    }

    public function destroy(Publisher $publisher)
    {
        $publisher->delete();
        return redirect()->route('admin.publishers.index')->with('success', 'Đã xóa nhà xuất bản thành công.');
    }
}
