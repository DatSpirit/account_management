<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Product;
use Faker\Factory as Faker;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        $userIds = Schema::hasTable('users') ? User::pluck('id')->toArray() : [];
        $productIds = Schema::hasTable('products') ? Product::pluck('id')->toArray() : [];

        if (empty($userIds)) $userIds = [null];
        if (empty($productIds)) $productIds = [null];

        $limit = 400;// Số lượng giao dịch muốn tạo

        for ($i = 0; $i < $limit; $i++) {
            
            $status = $faker->randomElement(['pending', 'success', 'success','success','failed', 'cancelled']);
            $isProcessed = $status === 'success';
            $processedAt = $isProcessed ? $faker->dateTimeThisMonth() : null;
            $createdAt = $faker->dateTimeBetween('-8 month', 'now');
            
            // =================================================================
            // Sử dụng microtime (mili-giây) và cộng thêm $i để đảm bảo duy nhất
            // =================================================================
            $timestampMicro = (int) (microtime(true) * 1000); 
            $orderCode = $timestampMicro + $i; // Mã đơn hàng duy nhất

            $responseData = [
                'code' => '00',
                'desc' => 'Success',
                'data' => [
                    'orderCode' => $orderCode,
                    'amount' => 0, 
                    'accountNumber' => '000123456789'
                ]
            ];

            $data = [
                'user_id'       => !empty($userIds) ? $faker->randomElement($userIds) : null,
                'product_id'    => !empty($productIds) ? $faker->randomElement($productIds) : null,
                'order_code'    => $orderCode, 
                'amount'        => $faker->randomFloat(0, 100000, 5000000),
                'status'        => $status,
                'description'   => 'Thanh toán đơn hàng #' . $orderCode,

                'is_processed'      => $isProcessed,
                'processed_at'      => $processedAt,
                
                // Cập nhật lại logic tạo signature để khớp với orderCode mới
                'webhook_signature' => $isProcessed ? hash('sha256', $orderCode . 'secret_key') : null,
                'webhook_payload'   => $isProcessed ? json_encode(['mock_payload' => true]) : null,

                'payment_reference' => $isProcessed ? 'FT' . $orderCode : null, // Dùng luôn orderCode cho đỡ trùng
                'payment_link_id'   => 'PL' . $faker->regexify('[A-Z0-9]{10}'),
                'account_number'    => '0333' . $faker->numberBetween(100000, 999999),
                
                'counter_account_name'      => ($status === 'success') ? strtoupper($faker->name) : null,
                'counter_account_number'    => ($status === 'success') ? $faker->bankAccountNumber : null,
                'counter_account_bank_id'   => ($status === 'success') ? $faker->randomElement(['970436', '970415', '970418']) : null,
                'counter_account_bank_name' => ($status === 'success') ? $faker->randomElement(['Vietcombank', 'VietinBank', 'BIDV', 'Techcombank']) : null,
                
                'transaction_datetime' => $createdAt,
                'currency'             => 'VND',

                'response_data' => json_encode($responseData),

                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];

            DB::table('transactions')->insert($data);
        }
    }
}