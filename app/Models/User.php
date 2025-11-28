<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // Thêm nếu dùng soft delete

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
        'is_admin',
        'last_login_at',
        'login_count',

        // Các trường liên quan đến quản lý thời hạn tài khoản
        'expires_at',
        'account_status',
        'account_notes',
    ];

    /**
     * Các trường cần ẩn khi serialize.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Các trường cần cast kiểu dữ liệu.
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
     * Quan hệ với bảng Transaction.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
