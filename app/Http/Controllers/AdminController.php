<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Hiển thị trang quản lý người dùng (Dashboard Admin).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        // 1. Lấy tất cả người dùng từ database
        $users = User::paginate(10);

        // 2. Trả về view 'admin.users' và truyền biến $users
        return view('admin.users', [
            'users' => $users,
        ]);
    }
}
