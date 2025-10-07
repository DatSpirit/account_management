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
        // Lấy danh sách người dùng, phân trang 10 user mỗi trang
        $users = User::paginate(10);

        // Trả về view với danh sách user
         return view('admin.users', compact('users'));
    
    }
}
