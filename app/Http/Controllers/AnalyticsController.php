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