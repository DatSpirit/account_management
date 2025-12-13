<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // ============================
        // 1 FILTERS
        // ============================
        $days      = $request->input('days', 30);
        $status    = $request->input('status');    // success / pending / failed
        $method    = $request->input('method');    // COINKEY / VND
        $productId = $request->input('product_id');

        $startDate = now()->subDays($days);
        $endDate   = now();

        // ============================
        // 2BASE QUERY
        // ============================
        $query = Transaction::where('transactions.user_id', $userId)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->with('product');

        if ($status)    $query->where('transactions.status', $status);
        if ($method)    $query->where('transactions.currency', $method);
        if ($productId) $query->where('transactions.product_id', $productId);

        $transactions = $query->orderBy('transactions.created_at', 'desc')->get();

        // ============================
        // 3) KPI SECTION
        // ============================
        $analytics = [
            'totalRevenue'   => $transactions->where('status', 'success')->sum('amount'),
            'ordersTotal'    => $transactions->count(),
            'ordersSuccess'  => $transactions->where('status', 'success')->count(),
            'successRate'    => $this->successRate($transactions),
            'avgOrder'       => $transactions->where('status', 'success')->avg('amount') ?? 0,
        ];

        // ============================
        // 4) REVENUE TREND (CHART)
        // ============================
        $trend = $transactions
            ->where('status', 'success')
            ->groupBy(fn($t) => $t->created_at->format('Y-m-d'))
            ->map(fn($rows, $date) => [
                'date'  => $date,
                'total' => $rows->sum('amount')
            ])
            ->values();

        // ============================
        // 5 PAYMENT DISTRIBUTION
        // ============================
        $paymentMethods = [
            'COINKEY' => $transactions->where('currency', 'COINKEY')->count(),
            'VND'     => $transactions->where('currency', 'VND')->count(),
        ];

        // ============================
        // 6 HOURLY HEATMAP (0–23)
        // ============================
        $hourly = array_fill(0, 24, 0);

        foreach ($transactions->where('status', 'success') as $t) {
            $hour = (int) $t->created_at->format('H');
            $hourly[$hour] += $t->amount;
        }

        // ============================
        // 7 TOP PRODUCTS
        // ============================
        $topProducts = Transaction::where('transactions.user_id', $userId)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.status', 'success')
            ->join('products', 'transactions.product_id', '=', 'products.id')
            ->select(
                'products.name AS product_name',
                DB::raw('COUNT(transactions.id) AS orders'),
                DB::raw('SUM(transactions.amount) AS total')
            )
            ->groupBy('products.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ============================
        // 8 TOP CUSTOMERS
        // ============================
        $topCustomers = Transaction::where('transactions.user_id', $userId)
            ->whereBetween('transactions.created_at', [$startDate, $endDate])
            ->where('transactions.status', 'success')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select(
                'users.name',
                DB::raw('COUNT(transactions.id) as orders'),
                DB::raw('SUM(transactions.amount) as total_spent')
            )
            ->groupBy('users.name')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();


        // ============================
        // 9 COHORT SUMMARY
        // ============================
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();

        $newUsersRevenue = $transactions
            ->where('status', 'success')
            ->filter(function ($t) use ($startDate, $endDate) {
                if (!$t->assigned_to_email) return false;

                return User::where('email', $t->assigned_to_email)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->exists();
            })
            ->sum('amount');

        $cohort = [
            'newUsers'        => $newUsers,
            'newUsersRevenue' => $newUsersRevenue,
            'repeatRate'      => $this->repeatRate($transactions),
        ];


        // ============================
        // PASS TO VIEW
        // ============================
        $products = Product::orderBy('name')->get();

        return view('analytics.index', compact(
            'analytics',
            'trend',
            'paymentMethods',
            'hourly',
            'topProducts',
            'topCustomers',
            'cohort',
            'products',
            'transactions',
            'startDate',
            'endDate',
            'days',
        ));
    }

    // ============================
    // HELPERS
    // ============================
    private function successRate($rows)
    {
        if ($rows->count() == 0) return 0;
        $success = $rows->where('status', 'success')->count();
        return round(($success / $rows->count()) * 100, 1);
    }

    private function repeatRate($transactions)
    {
        $customers = $transactions
            ->where('status', 'success')
            ->groupBy('assigned_to_email');

        if ($customers->count() == 0) return 0;

        $repeat = $customers->filter(fn($g) => $g->count() > 1)->count();

        return round(($repeat / $customers->count()) * 100, 1);
    }
    // Export page
    public function export()
    {
        return view('analytics.export');
    }

    // Export to Excel
    public function exportExcel(Request $request)
    {
        $dateRange = $request->input('date_range', 30);
        $userId = Auth::id();

        return Excel::download(
            new AnalyticsExport($userId, $dateRange),
            'analytics-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // Export to PDF
    public function exportPdf(Request $request)
    {
        $userId = Auth::id();
        $dateRange = $request->input('date_range', 30);
        $startDate = now()->subDays($dateRange);

        $data = [
            'transactions' => Transaction::where('user_id', $userId)
                ->where('created_at', '>=', $startDate)
                ->with('product')
                ->get(),
            'analytics' => $this->getAnalyticsData($userId, $startDate),
            'user' => Auth::user(),
        ];

        $pdf = Pdf::loadView('analytics.pdf-template', $data);

        return $pdf->download('analytics-report-' . now()->format('Y-m-d') . '.pdf');
    }

    // Export to CSV
    public function exportCsv(Request $request)
    {
        $userId = Auth::id();
        $dateRange = $request->input('date_range', 30);
        $startDate = now()->subDays($dateRange);

        $transactions = Transaction::where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->with('product')
            ->get();

        $filename = 'analytics-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Date', 'Order Code', 'Product', 'Amount', 'Status']);

            // Data
            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->created_at->format('Y-m-d H:i:s'),
                    $t->order_code,
                    $t->product->name ?? 'N/A',
                    $t->amount,
                    $t->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
