<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomExtensionPackage;

class CustomExtensionPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Gia hạn 1 ngày',
                'days' => 1,
                'duration_minutes' => 1440, // 1 ngày = 1440 phút
                'price_vnd' => 10000,
                'price_coinkey' => 10000,
                'sort_order' => 1,
            ],
            [
                'name' => 'Gia hạn 7 ngày',
                'days' => 7,
                'duration_minutes' => 10080, // 7 ngày = 10080 phút
                'price_vnd' => 60000,
                'price_coinkey' => 60000,
                'sort_order' => 2,
            ],
            [
                'name' => 'Gia hạn 30 ngày',
                'days' => 30,
                'duration_minutes' => 43200, // 30 ngày = 43200 phút
                'price_vnd' => 200000,
                'price_coinkey' => 200000,
                'sort_order' => 3,
            ],
            [
                'name' => 'Gia hạn 90 ngày',
                'days' => 90,
                'duration_minutes' => 129600, // 90 ngày
                'price_vnd' => 500000,
                'price_coinkey' => 500000,
                'sort_order' => 4,
            ],
            [
                'name' => 'Gia hạn 180 ngày',
                'days' => 180,
                'duration_minutes' => 259200, // 180 ngày
                'price_vnd' => 900000,
                'price_coinkey' => 900000,
                'sort_order' => 5,
            ],
            [
                'name' => 'Gia hạn 365 ngày',
                'days' => 365,
                'duration_minutes' => 525600, // 365 ngày
                'price_vnd' => 1500000,
                'price_coinkey' => 1500000,
                'sort_order' => 6,
            ],
        ];

        foreach ($packages as $package) {
            CustomExtensionPackage::updateOrCreate(
                ['days' => $package['days']],
                $package
            );
        }

        $this->command->info('✅ Created 6 custom extension packages');
    }
}