<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AccountExpirationService
{
    /**
     * Gia hạn tài khoản cho user
     * 
     * @param User $user
     * @param int $days Số ngày muốn gia hạn
     * @param string|null $note Ghi chú
     * @return User
     */
    public function extendAccount(User $user, int $days, ?string $note = null): User
    {
        // Nếu tài khoản chưa có ngày hết hạn hoặc đã hết hạn
        if (!$user->expires_at || Carbon::parse($user->expires_at)->isPast()) {
            // Gia hạn từ hôm nay
            $user->expires_at = Carbon::now()->addDays($days);
        } else {
            // Gia hạn từ ngày hết hạn hiện tại
            $user->expires_at = Carbon::parse($user->expires_at)->addDays($days);
        }
        
        $user->account_status = 'active';
        
        // Thêm ghi chú
        if ($note) {
            $existingNotes = $user->account_notes ?? '';
            $newNote = Carbon::now()->format('Y-m-d H:i:s') . ": Gia hạn {$days} ngày. {$note}";
            $user->account_notes = $existingNotes . "\n" . $newNote;
        }
        
        $user->save();
        
        Log::info("Account extended for user {$user->id}", [
            'days' => $days,
            'new_expiry' => $user->expires_at
        ]);
        
        return $user;
    }
    
    /**
     * Đặt ngày hết hạn cụ thể
     */
    public function setExpirationDate(User $user, Carbon $expiryDate, ?string $note = null): User
    {
        $user->expires_at = $expiryDate;
        $user->account_status = 'active';
        
        if ($note) {
            $existingNotes = $user->account_notes ?? '';
            $newNote = Carbon::now()->format('Y-m-d H:i:s') . ": Đặt hạn đến {$expiryDate->format('Y-m-d')}. {$note}";
            $user->account_notes = $existingNotes . "\n" . $newNote;
        }
        
        $user->save();
        
        return $user;
    }
    
    /**
     * Kiểm tra tài khoản có hết hạn không
     */
    public function isExpired(User $user): bool
    {
        if (!$user->expires_at) {
            return false; // Không giới hạn thời gian
        }
        
        return Carbon::parse($user->expires_at)->isPast();
    }
    
    /**
     * Lấy số ngày còn lại
     */
    public function getDaysRemaining(User $user): ?int
    {
        if (!$user->expires_at) {
            return null; // Không giới hạn
        }
        
        $days = Carbon::now()->diffInDays(Carbon::parse($user->expires_at), false);
        return max(0, (int)$days);
    }
    
    /**
     * Đánh dấu tài khoản hết hạn
     */
    public function markAsExpired(User $user): User
    {
        $user->account_status = 'expired';
        $user->save();
        
        Log::info("User {$user->id} marked as expired");
        
        return $user;
    }
    
    /**
     * Tạm ngưng tài khoản
     */
    public function suspendAccount(User $user, ?string $reason = null): User
    {
        $user->account_status = 'suspended';
        
        if ($reason) {
            $existingNotes = $user->account_notes ?? '';
            $newNote = Carbon::now()->format('Y-m-d H:i:s') . ": Tạm ngưng. Lý do: {$reason}";
            $user->account_notes = $existingNotes . "\n" . $newNote;
        }
        
        $user->save();
        
        Log::warning("User {$user->id} suspended", ['reason' => $reason]);
        
        return $user;
    }
    
    /**
     * Kích hoạt lại tài khoản
     */
    public function activateAccount(User $user, ?string $note = null): User
    {
        $user->account_status = 'active';
        
        if ($note) {
            $existingNotes = $user->account_notes ?? '';
            $newNote = Carbon::now()->format('Y-m-d H:i:s') . ": Kích hoạt lại. {$note}";
            $user->account_notes = $existingNotes . "\n" . $newNote;
        }
        
        $user->save();
        
        Log::info("User {$user->id} activated");
        
        return $user;
    }
}