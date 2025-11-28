<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\AccountExpirationService;

class CheckAccountExpiration
{
    protected $expirationService;

    public function __construct(AccountExpirationService $expirationService)
    {
        $this->expirationService = $expirationService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Nếu chưa đăng nhập thì bỏ qua
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Admin luôn được phép truy cập
        if ($user->is_admin) {
            return $next($request);
        }

        // Kiểm tra tài khoản bị suspend
        if ($user->account_status === 'suspended') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Tài khoản của bạn đã bị tạm ngưng. Vui lòng liên hệ admin.');
        }

        // Kiểm tra tài khoản hết hạn
        if ($this->expirationService->isExpired($user)) {
            // Tự động đánh dấu hết hạn
            if ($user->account_status !== 'expired') {
                $this->expirationService->markAsExpired($user);
            }

            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Tài khoản của bạn đã hết hạn. Vui lòng gia hạn để tiếp tục sử dụng.');
        }

        // Cảnh báo nếu sắp hết hạn (còn 3 ngày)
        $daysRemaining = $this->expirationService->getDaysRemaining($user);
        if ($daysRemaining !== null && $daysRemaining <= 3 && $daysRemaining > 0) {
            session()->flash('warning', "Tài khoản của bạn sẽ hết hạn sau {$daysRemaining} ngày. Vui lòng gia hạn!");
        }

        return $next($request);
    }
}