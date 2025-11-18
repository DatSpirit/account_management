<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Chỉ admin được xem
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Access denied.');
        }

        // Lọc theo nhiều tiêu chí
        $status = $request->get('status');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $transactions = Transaction::query()
            // Lọc theo trạng thái
            ->when($status && in_array($status, ['pending', 'success', 'failed', 'cancelled']), function($query) use ($status) {
                $query->where('status', $status);
            })
            // Tìm kiếm theo order_code, description, user name
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('order_code', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            })
            // Lọc theo ngày tạo
            ->when($dateFrom, function($query) use ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function($query) use ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            // Eager load relationships
            ->with(['user', 'product'])
            // Sắp xếp
            ->orderBy($sortBy, $sortOrder)
            ->paginate(15);

        // Thống kê chi tiết
        $stats = [
            'total' => Transaction::count(),
            'success' => Transaction::where('status', 'success')->count(),
            'failed' => Transaction::where('status', 'failed')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'cancelled' => Transaction::where('status', 'cancelled')->count(),
            
            // Tổng tiền
            'total_amount' => Transaction::where('status', 'success')->sum('amount'),
            'pending_amount' => Transaction::where('status', 'pending')->sum('amount'),
            
            // Thống kê theo thời gian
            'today' => Transaction::whereDate('created_at', today())->count(),
            'this_week' => Transaction::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => Transaction::whereMonth('created_at', now()->month)->count(),
        ];

        // Biểu đồ theo ngày (7 ngày gần nhất)
        $chartData = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "success" THEN amount ELSE 0 END) as revenue')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.transactions.all-transactions', [
            'transactions' => $transactions,
            'stats' => $stats,
            'chartData' => $chartData,
            
            // Filters
            'status' => $status,
            'search' => $search,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }
        /**
     * Xem chi tiết transaction
     */
    public function show($id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Access denied.');
        }

        $transaction = Transaction::with(['user', 'product'])->findOrFail($id);

        return view('admin.transactions.show', [
            'transaction' => $transaction
        ]);
    }

    /**
     * Cập nhật trạng thái transaction (manual override)
     */
    public function updateStatus(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'status' => 'required|in:pending,success,failed,cancelled',
            'note' => 'nullable|string|max:500'
        ]);

        $transaction = Transaction::findOrFail($id);
        
        $oldStatus = $transaction->status;
        $transaction->status = $request->status;
        
        if ($request->note) {
            $transaction->description .= " | Admin note: " . $request->note;
        }
        
        $transaction->save();

        return redirect()
            ->route('admin.transactions.all-transactions')
            ->with('success', 'Transaction status updated successfully');
    }

    /**
     * Export transactions to CSV
     */
    public function export(Request $request)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Access denied.');
        }

        $transactions = Transaction::with(['user', 'product'])
            ->when($request->status, function($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->date_from, function($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->get();

        $filename = 'transactions_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Order Code',
                'User Name',
                'User Email',
                'Product Name',
                'Amount',
                'Status',
                'Description',
                'Created At',
                'Updated At'
            ]);

            // Data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->order_code,
                    $transaction->user->name ?? 'N/A',
                    $transaction->user->email ?? 'N/A',
                    $transaction->product->name ?? 'N/A',
                    number_format($transaction->amount, 0, ',', '.'),
                    ucfirst($transaction->status),
                    $transaction->description,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}