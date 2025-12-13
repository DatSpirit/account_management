<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; 

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Các thuộc tính được phép gán.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'notes',
        'phone_number',
        'is_admin',
        'last_login_at',
        'login_count',

        // Các trường liên quan đến quản lý thời hạn tài khoản
        'expires_at',
        'account_status',
        'account_notes',
    ];

    /**
     * Các thuộc tính ẩn khi chuyển đổi sang mảng hoặc JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Các thuộc tính được chuyển đổi kiểu dữ liệu.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'last_login_at' => 'datetime',

            // Cast quan trọng cho hệ thống expiration
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Kiểm tra tài khoản có phải admin không.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Quan hệ với các bảng.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function coinkeyWallet()
    {
        return $this->hasOne(CoinkeyWallet::class);
    }

    public function coinkeyTransactions()
    {
        return $this->hasMany(CoinkeyTransaction::class);
    }

    public function productKeys()
    {
        return $this->hasMany(ProductKey::class);
    }

    // Helper methods liên quan đến expiration 

    public function isAdminUser(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Lấy hoặc tạo ví coinkey
     */

    public function getOrCreateWallet(): CoinkeyWallet
    {
        return $this->coinkeyWallet()->firstOrCreate([
            'user_id' => $this->id,
        ], [
            'balance' => 0,
            'total_deposited' => 0,
            'total_spent' => 0,
            'currency' => 'COINKEY',
            'is_locked' => false,        
        ]);
    }


     /**
     * Kiểm tra số dư coinkey
     */

    public function getCoinKeyBalance(): float
    {
        $wallet = $this->getOrCreateWallet();
        return (float) $wallet->balance;
    }

    /**
     * Kiểm tra có đủ coinkey không
     */

    public function hasEnoughCoinKey(float $amount): bool
    {
        return $this->getCoinKeyBalance() >= $amount;
    }
}