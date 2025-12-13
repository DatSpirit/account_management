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
            ->where('currency', '!=', 'COINKEY') // Chỉ lấy giao dịch tiền mặt (VND)
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
                ->where('currency', '!=', 'COINKEY') // Filter
                ->where('status', 'success')->count(),
            'pending' => Transaction::where('user_id', Auth::id())
                ->where('currency', '!=', 'COINKEY') // Filter
                ->where('status', 'pending')->count(),
            'failed' => Transaction::where('user_id', Auth::id())
                ->where('currency', '!=', 'COINKEY') // Filter
                ->whereIn('status', ['failed', 'cancelled'])->count(),
            'success_amount' => Transaction::where('user_id', Auth::id())
                ->where('currency', '!=', 'COINKEY') // Filter
                ->where('status', 'success')->sum('amount'),
            'pending_amount' => Transaction::where('user_id', Auth::id())
                ->where('currency', '!=', 'COINKEY') // Filter
                ->where('status', 'pending')->sum('amount'),
            'total_amount' => Transaction::where('user_id', Auth::id())
                ->where('currency', '!=', 'COINKEY') // Filter
                ->where('status', 'success')->sum('amount'),
        ];

        return view('transactions.index', compact('transactions', 'stats'));
    }
    public function show($id)
    {
        $transaction = Transaction::with(['user', 'product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('transactions.show', compact('transaction'));
    }

    //  Cancel transaction
    public function cancel($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($transaction->status === 'pending') {
            $transaction->update(['status' => 'cancelled']);
            return back()->with('success', 'Transaction cancelled successfully');
        }

        return back()->with('error', 'Cannot cancel this transaction');
    }

    //  Request refund
    public function requestRefund($id)
    {
        $transaction = Transaction::where('user_id', Auth::id())
            ->findOrFail($id);

        if ($transaction->status === 'success') {
            // Logic to create refund request
            return back()->with('success', 'Refund request submitted successfully');
        }

        return back()->with('error', 'Cannot request refund for this transaction');
    }

    //  Download invoice
    public function downloadInvoice($id)
    {
        $transaction = Transaction::with(['user', 'product'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Generate PDF invoice
        // Use package like dompdf or snappy

        return response()->download($pdfPath);
    }
}
