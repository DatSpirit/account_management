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

        // Filter by status (nếu status = 'all' thì không filter status)
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search logic: Nếu có search và (status = 'all' hoặc không filter status), tìm trên tất cả cột
        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            if (!$request->filled('status') || $request->status === 'all') {
                // Tìm trên tất cả các cột liên quan
                $query->where(function($q) use ($search) {
                    $q->where('order_code', 'like', $search)
                      ->orWhere('amount', 'like', $search)
                      ->orWhere('status', 'like', $search)
                      ->orWhere('description', 'like', $search)
                      ->orWhere('currency', 'like', $search)// Tìm theo loại tiền
                      ->orWhere('payment_link_id', 'like', $search)// Tìm theo ID liên kết thanh toán
                      ->orWhere('account_number', 'like', $search)// Tìm theo số tài khoản
                      ->orWhere('counter_account_name', 'like', $search)
                      ->orWhere('counter_account_number', 'like', $search)
                      ->orWhere('counter_account_bank_id', 'like', $search)
                      ->orWhere('counter_account_bank_name', 'like', $search)
                      ->orWhere('transaction_datetime', 'like', $search)// Tìm theo datetime dạng chuỗi
                      ->orWhere('payment_reference', 'like', $search)
                      ->orWhere('webhook_signature', 'like', $search)
                      ->orWhere('webhook_payload', 'like', $search)
                      ->orWhere('response_data', 'like', $search); // Nếu cần tìm JSON, có thể dùng JSON_CONTAINS nếu MySQL hỗ trợ
                });
            } else {
                // Nếu có filter status cụ thể, chỉ tìm trên order_code như cũ
                $query->where('order_code', 'like', $search);
            }
        }

        $transactions = $query->paginate(15);

        // Calculate stats
        $stats = [
            'success' => Transaction::where('user_id', Auth::id())
                ->where('currency', '!=', 'COINKEY')
                ->where('status', 'success')->count(),
            'pending' => Transaction::where('user_id', Auth::id())
                ->where('currency', '!=', 'COINKEY')
                ->where('status', 'pending')->count(),
            'failed' => Transaction::where('user_id', Auth::id())
                ->where('currency', '!=', 'COINKEY')
                ->whereIn('status', ['failed', 'cancelled'])->count(),
            'success_amount' => Transaction::where('user_id', Auth::id())
                ->where('currency', '!=', 'COINKEY')
                ->where('status', 'success')->sum('amount'),
            'pending_amount' => Transaction::where('user_id', Auth::id())
                ->where('currency', '!=', 'COINKEY')
                ->where('status', 'pending')->sum('amount'),
            'total_amount' => Transaction::where('user_id', Auth::id())
                ->where('currency', '!=', 'COINKEY')
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

    // Cancel transaction
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

    // Request refund
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

    // Download invoice
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