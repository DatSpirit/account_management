<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class VipPackageSeeder extends Seeder
{
    public function run(): void
    {
        $vipPackages = [
            [
                'name' => 'Gói Tân Thủ',
                'category' => 'vip_package',
                'price' => 50000,
                'description' => 'Gói dành cho người mới - Giảm tới 20%',
                'product_type' => 'package',
                'coinkey_amount' => 40000, // Giá gốc bằng Coinkey
                'duration_minutes' => 4320, // 3 ngày
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Gói Tuần Lễ VIP',
                'category' => 'vip_package',
                'price' => 150000,
                'description' => 'Gói 7 ngày - VIP 1 - Giảm 25%',
                'product_type' => 'package',
                'coinkey_amount' => 125000,
                'duration_minutes' => 10080, // 7 ngày
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Gói Tháng Bạc',
                'category' => 'vip_package',
                'price' => 350000,
                'description' => 'Gói 30 ngày - VIP 2 - 20%',
                'product_type' => 'package',
                'coinkey_amount' => 320000,
                'duration_minutes' => 43200, // 30 ngày
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Gói Tháng Vàng',
                'category' => 'vip_package',
                'price' => 650000,
                'description' => 'Gói 60 ngày - VIP 3 - 10%',
                'product_type' => 'package',
                'coinkey_amount' => 600000,
                'duration_minutes' => 86400, // 60 ngày
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Gói Premium Siêu Ưu Đãi',
                'category' => 'vip_package',
                'price' => 1200000,
                'description' => 'Gói 180 ngày - VIP 3+ - Giảm 10%',
                'product_type' => 'package',
                'coinkey_amount' => 1080000,
                'duration_minutes' => 259200, // 180 ngày
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($vipPackages as $package) {
            Product::updateOrCreate(
                [
                    'name' => $package['name'],
                    'category' => 'vip_package'
                ],
                $package
            );
        }

        $this->command->info('✅ VIP Packages seeded successfully!');
    }
}