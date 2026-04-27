<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books' => \App\Models\Book::count(),
            'total_users' => \App\Models\User::count(),
            'pending_books' => \App\Models\Book::where('status', 'pending')->count(),
            'total_points_recharged' => \App\Models\PointsTransaction::where('type', 'recharge')->where('status', 'completed')->sum('points'),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
