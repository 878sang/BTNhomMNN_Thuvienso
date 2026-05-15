<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::where('id', '!=', auth()->id())->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function show(\App\Models\User $user)
    {
        $transactions = $user->transactions()->latest()->paginate(10);
        $booksCount = $user->books()->count();
        $purchasedCount = $user->purchasedBooks()->count();
        
        return view('admin.users.show', compact('user', 'transactions', 'booksCount', 'purchasedCount'));
    }

    public function edit(\App\Models\User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, \App\Models\User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'points' => 'required|integer|min:0',
            'role' => 'required|in:user,admin',
        ]);

        $user->update($request->all());
        return redirect()->route('admin.users.index')->with('success', 'Đã cập nhật thông tin người dùng thành công.');
    }

    public function toggleStatus(\App\Models\User $user)
    {
        $user->update(['status' => !$user->status]);
        $statusText = $user->status ? 'kích hoạt' : 'khóa';
        return back()->with('success', "Tài khoản người dùng đã được {$statusText} thành công.");
    }

    public function destroy(\App\Models\User $user)
    {
        $user->delete();
        return back()->with('success', 'Đã xóa tài khoản người dùng vĩnh viễn.');
    }
}
