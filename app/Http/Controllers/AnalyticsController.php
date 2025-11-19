<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Get time range (default: 30 days)
        $days = $request->input('days', 30);
        $startDate = now()->subDays($days);

        // Calculate analytics
        $analytics = [
            'total_spent' => Transaction::where('user_id', $userId)
                ->where('status', 'success')
                ->where('created_at', '>=', $startDate)
                ->sum('amount'),
            
            'total_orders' => Transaction::where('user_id', $userId)
                ->where('created_at', '>=', $startDate)
                ->count(),
            
            'avg_transaction' => Transaction::where('user_id', $userId)
                ->where('status', 'success')
                ->where('created_at', '>=', $startDate)
                ->avg('amount') ?? 0,
            
            'success_rate' => $this->calculateSuccessRate($userId, $startDate),
        ];

        // Spending trend data (for chart)
        $spendingTrend = Transaction::where('user_id', $userId)
            ->where('status', 'success')
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('analytics.index', compact('analytics', 'spendingTrend'));
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

        $callback = function() use ($transactions) {
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

    // Helper method
    private function getAnalyticsData($userId, $startDate)
    {
        return [
            'total_spent' => Transaction::where('user_id', $userId)
                ->where('status', 'success')
                ->where('created_at', '>=', $startDate)
                ->sum('amount'),
            
            'total_orders' => Transaction::where('user_id', $userId)
                ->where('created_at', '>=', $startDate)
                ->count(),
            
            'avg_transaction' => Transaction::where('user_id', $userId)
                ->where('status', 'success')
                ->where('created_at', '>=', $startDate)
                ->avg('amount') ?? 0,
            
            'success_rate' => $this->calculateSuccessRate($userId, $startDate),
        ];
    }

    

    private function calculateSuccessRate($userId, $startDate)
    {
        $total = Transaction::where('user_id', $userId)
            ->where('created_at', '>=', $startDate)
            ->count();
        
        if ($total == 0) return 0;
        
        $success = Transaction::where('user_id', $userId)
            ->where('status', 'success')
            ->where('created_at', '>=', $startDate)
            ->count();
        
        return round(($success / $total) * 100, 1);
    }
}