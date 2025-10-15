<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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

        // Tạo dữ liệu tăng trưởng người dùng theo tháng (1–12)
        $growthData = [];
        for ($month = 1; $month <= 12; $month++) {
            $growthData[] = User::whereMonth('created_at', $month)->count();
        }

        // Đếm số admin & user thường (nếu cần thống kê thêm)
        $totalAdmins = User::where('is_admin', true)->count();
        $totalRegularUsers = User::where('is_admin', false)->count();

        // Trả dữ liệu ra view
        return view('dashboard.admin', compact(
            'totalUsers',
            'recentUsers',
            'growthData',
            'totalAdmins',
            'totalRegularUsers'
        ));
    }
}
