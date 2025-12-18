<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountExpirationService
{
    /**
     * Kiểm tra và cập nhật trạng thái tài khoản hết hạn
     */
    public function checkAndUpdateExpiredAccounts(): int
    {
        $expiredCount = 0;

        $expiredUsers = User::where('account_status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expiredUsers as $user) {
            $user->update([
                'account_status' => 'expired',
                'account_notes' => ($user->account_notes ?? '') . "\nExpired at: " . now()->toDateTimeString()
            ]);
            $expiredCount++;
        }

        return $expiredCount;
    }

    /**
     * Lấy số ngày còn lại của tài khoản
     */
    public function getDaysRemaining(User $user): ?int
    {
        if (!$user->expires_at) {
            return null; // Vô thời hạn
        }

        $days = now()->diffInDays($user->expires_at, false);
        return $days > 0 ? (int) $days : 0;
    }

    /**
     * Gia hạn tài khoản
     */
    public function extendAccount(User $user, int $days, string $reason = null): User
    {
        DB::beginTransaction();
        try {
            if ($user->expires_at && $user->expires_at->isFuture()) {
                // Nếu còn hạn, cộng thêm
                $newExpiresAt = $user->expires_at->addDays($days);
            } else {
                // Nếu hết hạn hoặc chưa có, tính từ hôm nay
                $newExpiresAt = now()->addDays($days);
            }

            $user->update([
                'expires_at' => $newExpiresAt,
                'account_status' => 'active',
                'account_notes' => ($user->account_notes ?? '') . 
                    "\nExtended {$days} days at " . now()->toDateTimeString() . 
                    ($reason ? " - Reason: {$reason}" : '')
            ]);

            DB::commit();
            return $user->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Đặt thời hạn cụ thể cho tài khoản
     */
    public function setExpiration(User $user, Carbon $expiresAt, string $reason = null): User
    {
        $user->update([
            'expires_at' => $expiresAt,
            'account_status' => $expiresAt->isFuture() ? 'active' : 'expired',
            'account_notes' => ($user->account_notes ?? '') . 
                "\nExpiration set to {$expiresAt->toDateTimeString()} at " . now()->toDateTimeString() .
                ($reason ? " - Reason: {$reason}" : '')
        ]);

        return $user->fresh();
    }

    /**
     * Tạm ngưng tài khoản
     */
    public function suspendAccount(User $user, string $reason): User
    {
        $user->update([
            'account_status' => 'suspended',
            'account_notes' => ($user->account_notes ?? '') . 
                "\nSuspended at " . now()->toDateTimeString() . " - Reason: {$reason}"
        ]);

        return $user->fresh();
    }

    /**
     * Kích hoạt lại tài khoản
     */
    public function activateAccount(User $user, string $reason = null): User
    {
        // Kiểm tra xem đã hết hạn chưa
        if ($user->expires_at && $user->expires_at->isPast()) {
            throw new \Exception('Cannot activate expired account. Please extend expiration first.');
        }

        $user->update([
            'account_status' => 'active',
            'account_notes' => ($user->account_notes ?? '') . 
                "\nActivated at " . now()->toDateTimeString() .
                ($reason ? " - Reason: {$reason}" : '')
        ]);

        return $user->fresh();
    }

    /**
     * Lấy danh sách tài khoản sắp hết hạn
     */
    public function getExpiringAccounts(int $days = 7)
    {
        return User::where('account_status', 'active')
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays($days)])
            ->orderBy('expires_at', 'asc')
            ->get();
    }

    /**
     * Thống kê tài khoản
     */
    public function getAccountStats(): array
    {
        return [
            'total' => User::count(),
            'active' => User::where('account_status', 'active')->count(),
            'expired' => User::where('account_status', 'expired')->count(),
            'suspended' => User::where('account_status', 'suspended')->count(),
            'expiring_7_days' => User::where('account_status', 'active')
                ->whereNotNull('expires_at')
                ->whereBetween('expires_at', [now(), now()->addDays(7)])
                ->count(),
            'expiring_30_days' => User::where('account_status', 'active')
                ->whereNotNull('expires_at')
                ->whereBetween('expires_at', [now(), now()->addDays(30)])
                ->count(),
        ];
    }
}