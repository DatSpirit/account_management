<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Chỉ admin được xem
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Access denied.');
        }

        // Lọc trạng thái: all / pending / success / failed
        $status = $request->get('status');

        $transactions = Transaction::when(
            $status && in_array($status, ['pending', 'success', 'failed']),
            fn($query) => $query->where('status', $status)
        )
        ->with(['user', 'product'])
        ->latest()
        ->paginate(10);

        // Thống kê tổng số giao dịch
        $totalTransactions = Transaction::count();
        $totalSuccess = Transaction::where('status', 'success')->count();
        $totalFailed = Transaction::where('status', 'failed')->count();
        $totalPending = Transaction::where('status', 'pending')->count();

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'status' => $status,
            'totalTransactions' => $totalTransactions,
            'totalSuccess' => $totalSuccess,
            'totalFailed' => $totalFailed,
            'totalPending' => $totalPending,
        ]);
    }
}
