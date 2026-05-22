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

        // Lọc theo số sao
        if ($request->has('stars') && $request->stars !== '') {
            $query->where('stars', $request->stars);
        }

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%");
                })->orWhereHas('book', function($q2) use ($search) {
                    $q2->where('title', 'like', "%$search%");
                })->orWhere('comment', 'like', "%$search%");
            });
        }

        $ratings = $query->latest()->paginate(15);
        return view('admin.ratings.index', compact('ratings'));
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return back()->with('success', 'Đã xóa đánh giá thành công.');
    }
}
