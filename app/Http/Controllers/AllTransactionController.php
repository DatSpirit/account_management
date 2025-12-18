<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AllTransactionController extends Controller
{
    public function index(Request $request)
    {
        // Chỉ admin được xem
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Access denied.');
        }

        $type = $request->get('type', 'cash'); // Mặc định là cash

        // Lấy các tham số lọc
        $status = $request->get('status');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $advanced = $request->get('advanced');


        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // 1. KHỞI TẠO QUERY
        $query = Transaction::query();

        // 2. LỌC THEO LOẠI (Cash / Coinkey)
        if ($type === 'cash') {
            $query->where(function ($q) {
                $q->where('currency', 'VND')
                    ->orWhereNull('currency');
            });
        } elseif ($type === 'coinkey') {
            $query->where('currency', 'COINKEY');
        }

        // 3. LỌC THEO TRẠNG THÁI
        if ($status && in_array($status, ['pending', 'success', 'failed', 'cancelled'])) {
            $query->where('status', $status);
        }

        // 4. LỌC THEO NGÀY
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // 5. TÌM KIẾM NÂNG CAO (Search Logic) - Đặt ở đây mới đúng
        if ($search) {
            $query->where(function ($q) use ($search, $advanced) {

                // Logic: Nếu không chọn Advanced (All) thì tìm hết. 
                // Nếu chọn cụ thể (ví dụ 'user'), chỉ tìm theo User.

                // A. Tìm theo ORDER (Mã đơn, Mô tả, Số tiền)
                // Áp dụng khi chọn 'All' hoặc 'order'
                if (!$advanced || $advanced === 'order') {
                    $q->orWhere('order_code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('amount', 'like', "%{$search}%");
                }

                // B. Tìm theo USER (Tên, Email)
                // Áp dụng khi chọn 'All' hoặc 'user'
                if (!$advanced || $advanced === 'user') {
                    $q->orWhereHas('user', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                }

                // C. Tìm theo PRODUCT (Tên sản phẩm)
                // Áp dụng khi chọn 'All' hoặc 'product'
                if (!$advanced || $advanced === 'product') {
                    $q->orWhereHas('product', function ($subQ) use ($search) {
                        $subQ->where('name', 'like', "%{$search}%");
                    });
                }

                // D. Tìm theo KEY (Mã Key, Key ID trong dữ liệu JSON)
                // Áp dụng khi chọn 'All' hoặc 'key'
                if (!$advanced || $advanced === 'key') {
                    $q->orWhere('response_data->key_code', 'like', "%{$search}%")
                        ->orWhere('response_data->key_id', 'like', "%{$search}%");
                }
            });
        }

        // 6. SẮP XẾP VÀ PHÂN TRANG (Thực hiện cuối cùng)
        $transactions = $query
            ->with(['user', 'product', 'productKey']) // Load quan hệ để tránh N+1 query
            ->orderBy($sortBy, $sortOrder)
            ->paginate(15)
            ->appends($request->query()); // Giữ lại tham số trên URL khi chuyển trang

        //  BASE QUERY - Tách riêng theo type
        $baseQuery = Transaction::query();

        // ✅ THỐNG KÊ THEO TYPE (tách riêng)
        $stats = [
            'total'          => (clone $baseQuery)->count(),
            'success'        => (clone $baseQuery)->where('status', 'success')->count(),
            'failed'         => (clone $baseQuery)->where('status', 'failed')->count(),
            'pending'        => (clone $baseQuery)->where('status', 'pending')->count(),
            'cancelled'      => (clone $baseQuery)->where('status', 'cancelled')->count(),

            // Tổng tiền (chỉ success)
            'total_amount'   => (clone $baseQuery)->where('status', 'success')->sum('amount'),
            'pending_amount' => (clone $baseQuery)->where('status', 'pending')->sum('amount'),

            // Thống kê theo thời gian (trong type hiện tại)
            'today'          => (clone $baseQuery)->whereDate('created_at', today())->count(),
            'this_week'      => (clone $baseQuery)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month'     => (clone $baseQuery)->whereMonth('created_at', now()->month)->count(),
        ];

        // ✅ BIỂU ĐỒ THEO NGÀY (7 ngày gần nhất - theo type)
        $chartData = (clone $baseQuery)
            ->select(
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
            'stats'        => $stats,
            'chartData'    => $chartData,
            'type'         => $type, // ✅ Pass type vào view

            // Filters
            'status'   => $status,
            'search'   => $search,
            'dateFrom' => $dateFrom,
            'dateTo'   => $dateTo,
            'sortBy'   => $sortBy,
            'sortOrder' => $sortOrder,
            'advanced' => $advanced,
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
        $type = $request->get('type', 'cash');

        $transactions = Transaction::with(['user', 'product'])
            // ✅ THÊM FILTER TYPE
            ->when($type === 'cash', function ($q) {
                $q->where('currency', 'VND')->orWhereNull('currency');
            })
            ->when($type === 'coinkey', function ($q) {
                $q->where('currency', 'COINKEY');
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->date_from, function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->get();

        $filename = 'transactions_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

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
