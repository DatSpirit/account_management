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
        // User::factory(10)->create();

        //    User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test' . uniqid() . '@example.com',
        //     ]);

        $this->call([
            UserSeeder::class,// Tạo thêm user ngẫu nhiên
            ProductSeeder::class, // Tạo thêm Product ngẫu nhiên
            TransactionSeeder::class, // Tạo thêm Transaction ngẫu nhiên
        ]);
    }
}

