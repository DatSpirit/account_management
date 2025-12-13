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
        //    UserSeeder::class,// Tạo thêm user ngẫu nhiên
        //  ProductSeeder::class, // Tạo thêm Product ngẫu nhiên
            //TransactionSeeder::class, // Tạo thêm Transaction ngẫu nhiên
        //    VipPackageSeeder::class, // Tạo gói VIP cố định
            OptimizeTransactionSeeder::class, 
        ]);  //lệnh gọi seeder: php artisan db:seed
    }
}

