<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Kiểm tra người dùng đã đăng nhập chưa
        if (Auth::check()) {
            // 2. Kiểm tra cột 'is_admin' có bằng true không
            if (Auth::user()->is_admin) {
                // Người dùng là Admin, cho phép tiếp tục
                return $next($request);
            }
        }
        // Nếu không đăng nhập hoặc không phải Admin, chuyển hướng về Dashboard và hiển thị thông báo
        return redirect()->route('dashboard')->with('status', 'You are not authorized to access this page.');

    }
}
