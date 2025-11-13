<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    /**
     * Hiển thị trang cá nhân (Dashboard) của người dùng đã đăng nhập.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;
        $now = now();

        // ----------------------------------------------------
        // 1. THỐNG KÊ GIAO DỊCH VÀ CHI TIÊU
        // ----------------------------------------------------
        $allTransactions = Transaction::where('user_id', $userId)->get();

        $transactionStats = $allTransactions->groupBy('status')->map->count();
        
        $totalSpend = $allTransactions->where('status', 'success')->sum('amount');
        $totalTransactions = $allTransactions->count();

        $stats = [
            'total_spend' => $totalSpend,
            'total_transactions' => $totalTransactions,
            'success' => $transactionStats->get('success', 0),
            'pending' => $transactionStats->get('pending', 0),
            'failed' => $transactionStats->get('failed', 0) + $transactionStats->get('cancelled', 0),
        ];

        // ----------------------------------------------------
        // 2. SẢN PHẨM ĐÃ MUA GẦN ĐÂY 
        // ----------------------------------------------------
        $productsBought = Transaction::with('product')
            ->where('user_id', $userId)
            ->where('status', 'success')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // ----------------------------------------------------
        // 3. HOẠT ĐỘNG GẦN ĐÂY 
        // ----------------------------------------------------
        $activities = [];

        if (property_exists($user, 'last_login_at') && $user->last_login_at) {
            $activities[] = [
                'desc' => "Đăng nhập thành công",
                'time' => $user->last_login_at->diffForHumans($now),
                'icon' => '🔑',
                'color' => 'indigo',
                'real_time' => $user->last_login_at,
            ];
        } else {
             $activities[] = [
                'desc' => "Tài khoản được tạo",
                'time' => $user->created_at->diffForHumans($now),
                'icon' => '🎉',
                'color' => 'emerald',
                'real_time' => $user->created_at,
            ];
        }

        if ($latestSuccess = $productsBought->first()) {
             $activities[] = [
                'desc' => "Hoàn tất thanh toán đơn hàng #{$latestSuccess->order_code}",
                'time' => $latestSuccess->created_at->diffForHumans($now),
                'icon' => '💰',
                'color' => 'green',
                'real_time' => $latestSuccess->created_at,
            ];
        }

        if ($user->updated_at->gt($user->created_at)) {
             $activities[] = [
                'desc' => "Cập nhật hồ sơ cá nhân",
                'time' => $user->updated_at->diffForHumans($now),
                'icon' => '✍️',
                'color' => 'purple',
                'real_time' => $user->updated_at,
            ];
        }

        usort($activities, fn($a, $b) => $b['real_time'] <=> $a['real_time']);
        $activities = array_slice($activities, 0, 4);

        // ----------------------------------------------------
        // 4. DỮ LIỆU BIỂU ĐỒ (Chi tiêu 7 ngày)
        // ----------------------------------------------------
        $dateRange = collect(range(0, 6))->map(fn($day) => $now->copy()->subDays($day)->format('Y-m-d'));
        
        $chartData = Transaction::where('user_id', $userId)
            ->where('status', 'success')
            ->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(created_at)'))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->pluck('total', 'date')
            ->all();

        $chartLabels = [];
        $chartTotals = [];

        foreach ($dateRange as $date) {
            $chartLabels[] = date('d/m', strtotime($date));
            $chartTotals[] = $chartData[$date] ?? 0;
        }

        $chartLabels = array_reverse($chartLabels);
        $chartTotals = array_reverse($chartTotals);
        
        // ----------------------------------------------------
        // TRẢ VỀ VIEW
        // ----------------------------------------------------
        return view('dashboard.user', [ 
            'user' => $user,
            'stats' => $stats,
            'productsBought' => $productsBought,
            'activities' => $activities,
            'chartLabels' => $chartLabels,
            'chartTotals' => $chartTotals,
        ]);
    }
}