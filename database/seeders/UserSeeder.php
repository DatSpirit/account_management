<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // 1. TÀI KHOẢN ADMIN (Để kiểm thử)
        User::create([
            'name' => 'Admin Tester',
            'email' => 'admin@test.com', // Email dùng để đăng nhập
            'password' => Hash::make('password'), // Mật khẩu là 'password'
            'is_admin' => true, // Đánh dấu là Admin
        ]);

          // 1. TÀI KHOẢN ADMIN (Để kiểm thử)
        User::create([
            'name' => 'Thanh Dat',
            'email' => 'datpt@gmail.com', // Email dùng để đăng nhập
            'password' => Hash::make('12345678'), // Mật khẩu 
            'is_admin' => true, // Đánh dấu là Admin
        ]);



         // Tạo 200 user ngẫu nhiên : php artisan db:seed
        User::factory()->count(200)->create();
    }
}
