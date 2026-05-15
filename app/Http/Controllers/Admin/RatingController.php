<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $query = Rating::with(['user', 'book']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            })->orWhereHas('book', function($q) use ($search) {
                $q->where('title', 'like', "%$search%");
            });
        }

        $ratings = $query->latest()->paginate(10);
        return view('admin.ratings.index', compact('ratings'));
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return back()->with('success', 'Đã xóa đánh giá thành công.');
    }
}
