<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Product;
use App\Services\AccountExpirationService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    protected $expirationService;

    public function __construct(AccountExpirationService $expirationService)
    {
        $this->expirationService = $expirationService;
    }

    /**
     * Hiển thị trang Dashboard của Admin
     */
    public function index(Request $request)
    {
        // Lấy filter period (mặc định 30 ngày)
        $period = $request->get('period', 30);

        // ===== THỐNG KÊ TỔNG QUAN =====
        $totalUsers = User::count();
        $recentUsers = User::latest()->take(10)->get();

        // ===== TÍNH TOÁN TĂNG TRƯỞNG THEO THÁNG =====
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $usersThisMonth = User::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $lastMonth = now()->subMonth();
        $usersLastMonth = User::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        if ($usersLastMonth > 0) {
            $growthPercentage = (($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100;
        } else {
            $growthPercentage = $usersThisMonth > 0 ? 100 : 0;
        }
        $growthPercentage = round($growthPercentage, 1);
        $isGrowth = $growthPercentage >= 0;

        // ===== TĂNG TRƯỞNG THEO NĂM =====
        $growthData = [];
        for ($month = 1; $month <= 12; $month++) {
            $growthData[] = User::whereMonth('created_at', $month)
                ->whereYear('created_at', $currentYear)
                ->count();
        }

        $totalGrowthThisYear = array_sum($growthData);
        $lastYear = now()->subYear()->year;
        $totalUsersLastYear = User::whereYear('created_at', $lastYear)->count();

        if ($totalUsersLastYear > 0) {
            $yearlyGrowthPercentage = (($totalGrowthThisYear - $totalUsersLastYear) / $totalUsersLastYear) * 100;
        } else {
            $yearlyGrowthPercentage = $totalGrowthThisYear > 0 ? 100 : 0;
        }
        $yearlyGrowthPercentage = round($yearlyGrowthPercentage, 1);
        $isYearlyGrowth = $yearlyGrowthPercentage >= 0;

        // ===== BIỂU ĐỒ TRÒN 1: PHÂN LOẠI NGƯỜI DÙNG =====
        $userDistribution = [
            'new' => User::where('created_at', '>=', now()->subDays($period))->count(),
            'existing' => User::where('created_at', '<', now()->subDays($period))->count(),
            'expired' => User::where(function ($q) {
                $q->where('account_status', 'expired')
                    ->orWhere(function ($q2) {
                        $q2->where('account_status', 'active')
                            ->whereNotNull('expires_at')
                            ->where('expires_at', '<', now());
                    });
            })->count(),
            'deleted' => User::onlyTrashed()->count() ?? 0,
        ];

        // ===== BIỂU ĐỒ TRÒN 2: TRẠNG THÁI HOẠT ĐỘNG =====
        $activityStatus = [
            'very_active' => User::where('last_login_at', '>=', now()->subDays(1))->count(), // Hoạt động hôm nay
            'active' => User::whereBetween('last_login_at', [now()->subDays(7), now()->subDays(1)])->count(), // 1-7 ngày
            'inactive' => User::whereBetween('last_login_at', [now()->subDays(30), now()->subDays(7)])->count(), // 7-30 ngày
            'dormant' => User::where('last_login_at', '<', now()->subDays(30))
                ->orWhereNull('last_login_at')
                ->count(), // Trên 30 ngày hoặc chưa login
        ];

        // ===== TOP 10 NGƯỜI MUA HÀNG NHIỀU NHẤT =====
        $topBuyers = Transaction::select('user_id', DB::raw('COUNT(*) as purchase_count'))
            ->where('status', 'success')
            ->groupBy('user_id')
            ->orderBy('purchase_count', 'desc')
            ->limit(10)
            ->with('user')
            ->get()
            ->map(function ($item) {
                return [
                    'user' => $item->user,
                    'purchase_count' => $item->purchase_count,
                    'total_spent' => Transaction::where('user_id', $item->user_id)
                        ->where('status', 'success')
                        ->sum('amount')
                ];
            });

        // ===== TOP 10 NGƯỜI TIÊU TIỀN NHIỀU NHẤT =====
        $topSpenders = Transaction::select('user_id', DB::raw('SUM(amount) as total_spent'))
            ->where('status', 'success')
            ->groupBy('user_id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->with('user')
            ->get()
            ->map(function ($item) {
                return [
                    'user' => $item->user,
                    'total_spent' => $item->total_spent,
                    'purchase_count' => Transaction::where('user_id', $item->user_id)
                        ->where('status', 'success')
                        ->count()
                ];
            });

        // ===== SẢN PHẨM BÁN CHẠY NHẤT =====
        $topProducts = Transaction::select('product_id', DB::raw('COUNT(*) as sales_count'), DB::raw('SUM(amount) as revenue'))
            ->where('status', 'success')
            ->whereNotNull('product_id')
            ->groupBy('product_id')
            ->orderBy('sales_count', 'desc')
            ->limit(10)
            ->with('product')
            ->get()
            ->map(function ($item) {
                return [
                    'product' => $item->product,
                    'sales_count' => $item->sales_count,
                    'revenue' => $item->revenue
                ];
            });

        // ===== NGƯỜI DÙNG SẮP HẾT HẠN =====
        $expiringUsers = User::where('account_status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(7))
            ->orderBy('expires_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'user' => $user,
                    'days_remaining' => $this->expirationService->getDaysRemaining($user),
                    'expires_at' => $user->expires_at
                ];
            });

        // ===== THỐNG KÊ GIAO DỊCH =====
        $transactionStats = [
            'total' => Transaction::count(),
            'success' => Transaction::where('status', 'success')->count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'failed' => Transaction::where('status', 'failed')->count(),
            'total_revenue' => Transaction::where('status', 'success')->sum('amount'),
            'today_revenue' => Transaction::where('status', 'success')
                ->whereDate('created_at', today())
                ->sum('amount'),
        ];

        // ===== BIỂU ĐỒ DOANH THU 7 NGÀY =====
        $revenueChart = Transaction::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(CASE WHEN status = "success" THEN amount ELSE 0 END) as revenue'),
            DB::raw('COUNT(CASE WHEN status = "success" THEN 1 END) as orders')
        )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('dashboard.admin', compact(
            'totalUsers',
            'recentUsers',
            'growthData',
            'usersThisMonth',
            'usersLastMonth',
            'growthPercentage',
            'isGrowth',
            'totalGrowthThisYear',
            'yearlyGrowthPercentage',
            'isYearlyGrowth',
            'period',

            // Biểu đồ tròn
            'userDistribution',
            'activityStatus',

            // Top lists
            'topBuyers',
            'topSpenders',
            'topProducts',

            // Người dùng sắp hết hạn
            'expiringUsers',

            // Thống kê giao dịch
            'transactionStats',
            'revenueChart'
        ));
    }
}