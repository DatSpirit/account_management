<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Admin luôn được phép truy cập
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Kiểm tra tài khoản bị suspended
        if ($user->account_status === 'suspended') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your account has been suspended. Please contact support.');
        }

        // Kiểm tra tài khoản hết hạn
        if ($user->expires_at && $user->expires_at->isPast()) {
            // Cập nhật status nếu chưa được cập nhật
            if ($user->account_status !== 'expired') {
                $user->update(['account_status' => 'expired']);
            }

            // Cho phép truy cập nhưng hiển thị warning
            session()->flash('warning', 'Your account has expired. Please renew to continue using all features.');
        }

        return $next($request);
    }
}