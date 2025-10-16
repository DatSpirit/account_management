<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Hiển thị trang Dashboard của Admin
     */
    public function index()
    {
        // Tổng số người dùng
        $totalUsers = User::count();

        // Lấy người dùng mới nhất (giới hạn 5)
        $recentUsers = User::latest()->take(5)->get();

        // ===== TÍNH TOÁN TĂNG TRƯỞNG THEO THÁNG =====
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Người dùng tháng này
        $usersThisMonth = User::whereMonth('created_at', $currentMonth)
                              ->whereYear('created_at', $currentYear)
                              ->count();

        // Người dùng tháng trước
        $lastMonth = now()->subMonth();
        $usersLastMonth = User::whereMonth('created_at', $lastMonth->month)
                              ->whereYear('created_at', $lastMonth->year)
                              ->count();

        // Tính % tăng trưởng tháng (so với tháng trước)
        if ($usersLastMonth > 0) {
            $growthPercentage = (($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100;
        } else {
            $growthPercentage = $usersThisMonth > 0 ? 100 : 0;
        }
        $growthPercentage = round($growthPercentage, 1);
        $isGrowth = $growthPercentage >= 0;

        // ===== TÍNH TOÁN TĂNG TRƯỞNG THEO NĂM =====
        // Tạo dữ liệu tăng trưởng người dùng theo tháng (1–12)
        $growthData = [];
        for ($month = 1; $month <= 12; $month++) {
            $growthData[] = User::whereMonth('created_at', $month)
                               ->whereYear('created_at', $currentYear)
                               ->count();
        }

        // Tổng người dùng mới năm nay
        $totalGrowthThisYear = array_sum($growthData);

        // Tổng người dùng năm trước
        $lastYear = now()->subYear()->year;
        $totalUsersLastYear = User::whereYear('created_at', $lastYear)->count();

        // % tăng trưởng so với năm trước
        if ($totalUsersLastYear > 0) {
            $yearlyGrowthPercentage = (($totalGrowthThisYear - $totalUsersLastYear) / $totalUsersLastYear) * 100;
        } else {
            $yearlyGrowthPercentage = $totalGrowthThisYear > 0 ? 100 : 0;
        }
        $yearlyGrowthPercentage = round($yearlyGrowthPercentage, 1);
        $isYearlyGrowth = $yearlyGrowthPercentage >= 0;

        // ===== THỐNG KÊ ADMIN & USER THƯỜNG =====
        $totalAdmins = User::where('is_admin', true)->count();
        $totalRegularUsers = User::where('is_admin', false)->count();

        // Trả dữ liệu ra view
        return view('dashboard.admin', compact(
            'totalUsers',
            'recentUsers',
            'growthData',
            'totalAdmins',
            'totalRegularUsers',
            'usersThisMonth',
            'usersLastMonth',
            'growthPercentage',
            'isGrowth',
            'totalGrowthThisYear',
            'yearlyGrowthPercentage',
            'isYearlyGrowth'
        ));
    }
}