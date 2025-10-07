<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // sử dụng Auth
use Illuminate\Routing\Controller;
class UserController extends Controller
{
    /**
     * Hiển thị trang cá nhân (profile) của người dùng đã đăng nhập.
     * Trang này được bảo vệ bởi middleware 'auth'.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Lấy đối tượng người dùng hiện tại
        $user = Auth::user();

        // Trả về view 'user.profile' và truyền dữ liệu $user sang view 
        return view('user.profile', compact('user'));
    }
}
