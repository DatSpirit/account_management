<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
        'product_type',      // 'coinkey' hoặc 'package'
        'coinkey_amount',    // Số lượng Coinkey nhận được (nếu nạp) HOẶC Giá bán bằng Coinkey (nếu là gói)
        'duration_minutes',  // Chỉ dùng cho 'package'
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'coinkey_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // --- Helper Methods ---

    // Kiểm tra đây có phải là gói nạp tiền không
    public function isCoinkeyPack(): bool
    {
        return $this->product_type === 'coinkey';
    }

    // Kiểm tra đây có phải là gói dịch vụ (Key) không
    public function isServicePackage(): bool
    {
        return $this->product_type === 'package';
    }

    // Kiểm tra xem sản phẩm này có cho phép thanh toán bằng Ví không
    public function allowWalletPayment(): bool
    {
        // Gói nạp Coinkey thì KHÔNG được mua bằng Ví (tránh loop)
        // Gói dịch vụ phải có giá Coinkey set > 0
        return $this->isServicePackage() && $this->coinkey_amount > 0;
    }
}