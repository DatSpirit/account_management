<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::where('user_id', Auth::id())
            ->with('product')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order code
        if ($request->filled('search')) {
            $query->where('order_code', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->paginate(15);

        // Calculate stats
        $stats = [
            'success' => Transaction::where('user_id', Auth::id())
                ->where('status', 'success')->count(),
            'pending' => Transaction::where('user_id', Auth::id())
                ->where('status', 'pending')->count(),
            'failed' => Transaction::where('user_id', Auth::id())
                ->whereIn('status', ['failed', 'cancelled'])->count(),
            'success_amount' => Transaction::where('user_id', Auth::id())
                ->where('status', 'success')->sum('amount'),
            'pending_amount' => Transaction::where('user_id', Auth::id())
                ->where('status', 'pending')->sum('amount'),
            'total_amount' => Transaction::where('user_id', Auth::id())
                ->where('status', 'success')->sum('amount'),
        ];

        return view('transactions.index', compact('transactions', 'stats'));
    }
}