<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id ?? null,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => [
                        'name' => Auth::user()->name,
                        'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&color=fff',
                    ],
                    'created_at' => $comment->created_at->format('d/m/Y'),
                    'replies' => [],
                ]
            ]);
        }

        return back()->with('success', 'Đã gửi bình luận của bạn.');
    }

    public function destroy(Comment $comment)
    {
        if (auth()->user()->id !== $comment->user_id && !auth()->user()->isAdmin()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Không có quyền xóa bình luận này.'], 403);
            }
            return back()->with('error', 'Không có quyền xóa bình luận này.');
        }

        $comment->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Đã xóa bình luận.');
    }
}
