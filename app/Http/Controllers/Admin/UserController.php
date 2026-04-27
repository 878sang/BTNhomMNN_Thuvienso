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

    public function toggleStatus(\App\Models\User $user)
    {
        $user->update(['status' => !$user->status]);
        $statusText = $user->status ? 'kích hoạt' : 'khóa';
        return back()->with('success', "Tài khoản người dùng đã được {$statusText} thành công.");
    }

    public function destroy(\App\Models\User $user)
    {
        // Instead of hard delete, we could just deactivate, but here we provide delete too
        $user->delete();
        return back()->with('success', 'Đã xóa tài khoản người dùng vĩnh viễn.');
    }
}
