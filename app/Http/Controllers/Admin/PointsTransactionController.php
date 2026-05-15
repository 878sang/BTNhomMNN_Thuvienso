<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PointsTransaction;
use Illuminate\Http\Request;

class PointsTransactionController extends Controller
{
    public function index()
    {
        $transactions = PointsTransaction::with('user')->latest()->paginate(20);
        return view('admin.transactions.index', compact('transactions'));
    }
}
