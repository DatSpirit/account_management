<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_code',
        'amount',
        'status',
        'description',
        'response_data',
    ];

    protected $casts = [
        'response_data' => 'array',
        'amount' => 'decimal:2',
    ];

    /**
     * Liên kết tới người dùng (user thực hiện giao dịch)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Liên kết tới sản phẩm được mua
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Kiểm tra giao dịch thành công
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Kiểm tra giao dịch thất bại
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Kiểm tra giao dịch đang chờ xử lý
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
