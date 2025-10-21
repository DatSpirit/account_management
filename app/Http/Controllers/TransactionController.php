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

        $transactions = Transaction::when($status && in_array($status, ['pending', 'success', 'failed']),
            fn($query) => $query->where('status', $status)
        )
        ->with(['user', 'product'])
        ->latest()
        ->paginate(10);

        return view('admin.transactions.index', [
            'transactions' => $transactions,
            'status' => $status,
        ]);
    }
}
