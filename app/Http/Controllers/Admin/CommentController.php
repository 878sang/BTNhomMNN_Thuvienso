<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'book']);

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                })->orWhereHas('book', function($q2) use ($search) {
                    $q2->where('title', 'like', "%$search%");
                })->orWhere('content', 'like', "%$search%");
            });
        }

        $comments = $query->latest()->paginate(15);
        return view('admin.comments.index', compact('comments'));
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Đã xóa bình luận thành công.');
    }
}
