<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo các gói nạp Coinkey (Top-up)
        $coinPackages = [
            ['name' => 'Gói Khởi Động', 'coin' => 20000, 'price' => 20000],
            ['name' => 'Gói Cơ Bản', 'coin' => 50000, 'price' => 50000],
            ['name' => 'Gói Tiêu Chuẩn', 'coin' => 100000, 'price' => 100000],
            ['name' => 'Gói Cao Cấp', 'coin' => 200000, 'price' => 200000],
            ['name' => 'Gói Vip', 'coin' => 500000, 'price' => 500000],
        ];

        foreach ($coinPackages as $pkg) {
            Product::create([
                'name' => $pkg['name'],
                'category' => 'Top-up',
                'price' => $pkg['price'],
                'description' => 'Nạp ' . number_format($pkg['coin']) . ' Coinkey vào ví. Sử dụng để mua các gói dịch vụ.',
                'product_type' => 'coinkey',
                'coinkey_amount' => $pkg['coin'], // Số coin nhận được
                'duration_minutes' => null,
                'is_active' => true,
            ]);
        }

        // 2. Tạo các gói dịch vụ (Service Packages)
        $servicePackages = [
            ['name' => 'Key 1 Ngày', 'days' => 1, 'price' => 15000, 'coin' => 15000],
            ['name' => 'Key 3 Ngày', 'days' => 3, 'price' => 40000, 'coin' => 40000],
            ['name' => 'Key 1 Tuần', 'days' => 7, 'price' => 80000, 'coin' => 80000],
            ['name' => 'Key 1 Tháng', 'days' => 30, 'price' => 300000, 'coin' => 300000],
        ];

        foreach ($servicePackages as $pkg) {
            Product::create([
                'name' => $pkg['name'],
                'category' => 'Service',
                'price' => $pkg['price'],
                'description' => 'Gói bản quyền sử dụng trong ' . $pkg['days'] . ' ngày. Hỗ trợ đầy đủ tính năng.',
                'product_type' => 'package',
                'coinkey_amount' => $pkg['coin'], // Giá bán bằng Coin
                'duration_minutes' => $pkg['days'] * 24 * 60,
                'is_active' => true,
            ]);
        }
    }
}