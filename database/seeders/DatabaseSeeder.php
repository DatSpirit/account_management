<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,// Tạo thêm user ngẫu nhiên
            ProductSeeder::class, // Tạo thêm Product ngẫu nhiên
            CustomExtensionPackageSeeder::class, // Tạo gói gia hạn tùy chỉnh
            VipPackageSeeder::class, // Tạo các gói VIP
            OptimizeTransactionSeeder::class, // Tạo các giao dịch tối ưu hóa
        ]);  //lệnh gọi seeder: php artisan db:seed
    }
}

