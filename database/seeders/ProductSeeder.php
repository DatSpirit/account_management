<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Electronics', 'Books', 'Clothes', 'Toys', 'Home'];

        for ($i = 1; $i <= 10; $i++) {
            Product::create([
                'name' => 'Product ' . $i,
                'category' => $categories[array_rand($categories)],
                'price' => rand(2000, 100000),
                'description' => 'This is a sample description for product ' . $i . '. ' . Str::random(20),
            ]);
        }
    }
}
