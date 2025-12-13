<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Product;
use App\Models\CoinkeyWallet;
use Faker\Factory as Faker;
use Carbon\Carbon;

class OptimizeTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Lấy dữ liệu thực
        $users = User::all();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            $this->command->info('⚠️ Cần có Users và Products trước khi chạy TransactionSeeder.');
            return;
        }

        $limit = 50; // Số lượng giao dịch mẫu

        foreach (range(1, $limit) as $i) {
            $user = $users->random();
            $product = $products->random();
            
            // Random trạng thái (ưu tiên thành công để có dữ liệu test)
            $status = $faker->randomElement(['success', 'success', 'success', 'pending', 'cancelled', 'failed']);
            $isSuccess = $status === 'success';
            
            // Thời gian tạo
            $createdAt = $faker->dateTimeBetween('-10 months', 'now');
            $processedAt = $isSuccess ? (clone $createdAt)->modify('+'.rand(1, 10).' minutes') : null;

            // Tạo mã đơn hàng
            $timestampMicro = (int) (microtime(true) * 1000);
            $orderCode = $timestampMicro + $i;

            // --- 1. XÁC ĐỊNH LOẠI GIAO DỊCH & TIỀN TỆ ---
            $currency = 'VND';
            $amount = $product->price;
            $paymentMethod = 'cash'; 

            if ($product->product_type === 'coinkey') {
                $paymentMethod = 'cash';
                $currency = 'VND';
                $amount = $product->price;
            } else {
                if ($product->coinkey_amount > 0 && $faker->boolean(40)) { 
                    $paymentMethod = 'wallet';
                    $currency = 'COINKEY';
                    $amount = $product->coinkey_amount;
                }
            }

            // --- 2. GIẢ LẬP DỮ LIỆU CUSTOM KEY ---
            $isCustomKey = ($product->product_type === 'package' && $faker->boolean(20));
            $customKeyCode = $isCustomKey ? strtoupper(Str::random(4) . '-' . Str::random(4)) : null;
            
            $responseData = [];
            if ($isCustomKey) {
                $responseData = [
                    'type' => 'custom_key_purchase',
                    'key_code' => $customKeyCode,
                    'duration_minutes' => $product->duration_minutes ?? 30,
                    'product_id' => $product->id
                ];
                $description = "Custom Key: $customKeyCode";
            } else {
                $description = $paymentMethod === 'wallet' 
                    ? "Mua {$product->name} (Ví Coinkey)" 
                    : "Thanh toán đơn hàng #{$orderCode}";
            }

            // --- 3. TẠO TRANSACTION CHÍNH ---
            $transactionId = DB::table('transactions')->insertGetId([
                'user_id'       => $user->id,
                'product_id'    => $product->id,
                'order_code'    => $orderCode,
                'amount'        => $amount,
                'status'        => $status,
                'description'   => $description,
                'currency'      => $currency,
                'is_processed'  => $isSuccess,
                'processed_at'  => $processedAt,
                'payment_link_id' => $currency === 'VND' ? 'PL' . Str::random(10) : null,
                'account_number'  => $currency === 'VND' ? '0333' . $faker->numberBetween(100000, 999999) : null,
                'response_data' => json_encode($responseData),
                'created_at' => $createdAt,
                'updated_at' => $processedAt ?? $createdAt,
            ]);

            // --- 4. HỆ QUẢ ĐỒNG BỘ DỮ LIỆU (NẾU SUCCESS) ---
            if ($isSuccess) {
                
                // A. Tạo Key nếu là package
                if ($product->product_type === 'package') {
                    DB::table('product_keys')->insert([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'transaction_id' => $transactionId,
                        'key_code' => $isCustomKey ? $customKeyCode : strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4)),
                        'key_type' => $isCustomKey ? 'custom' : 'auto_generated',
                        'duration_minutes' => $product->duration_minutes,
                        'key_cost' => $currency === 'COINKEY' ? $amount : 0,
                        'status' => 'active',
                        'activated_at' => $processedAt,
                        'expires_at' => Carbon::parse($processedAt)->addMinutes($product->duration_minutes ?? 0),
                        'created_at' => $processedAt,
                        'updated_at' => $processedAt,
                    ]);
                    $product->increment('sold_count');
                }

                // B. Xử lý Ví Coinkey
                // FIX LỖI: Cung cấp giá trị mặc định khi tạo mới
                $wallet = CoinkeyWallet::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'balance' => 0, 
                        'total_deposited' => 0, 
                        'total_spent' => 0
                    ]
                );

                // FIX LỖI: Đảm bảo currentBalance không bao giờ null
                $currentBalance = $wallet->balance ?? 0;
                
                if ($paymentMethod === 'wallet') {
                    // Trừ tiền
                    DB::table('coinkey_transactions')->insert([
                        'wallet_id' => $wallet->id,
                        'user_id' => $user->id,
                        'type' => 'purchase',
                        'amount' => -$amount,
                        'balance_before' => $currentBalance, // Sử dụng biến đã check null
                        'balance_after' => $currentBalance - $amount,
                        'description' => $description,
                        'reference_type' => 'Product',
                        'reference_id' => $product->id,
                        'created_at' => $processedAt,
                        'updated_at' => $processedAt,
                    ]);
                    
                    $wallet->increment('total_spent', $amount);
                    // Giả lập trừ tiền trong ví (để các vòng lặp sau tính toán đúng hơn)
                    $wallet->decrement('balance', $amount);
                    
                } elseif ($product->product_type === 'coinkey') {
                    // Nạp tiền
                    $coinAmount = $product->coinkey_amount;
                    
                    DB::table('coinkey_transactions')->insert([
                        'wallet_id' => $wallet->id,
                        'user_id' => $user->id,
                        'type' => 'deposit',
                        'amount' => $coinAmount,
                        'balance_before' => $currentBalance, // Sử dụng biến đã check null
                        'balance_after' => $currentBalance + $coinAmount,
                        'description' => "Nạp {$coinAmount} Coinkey (Đơn #{$orderCode})",
                        'reference_type' => 'Transaction',
                        'reference_id' => $transactionId,
                        'created_at' => $processedAt,
                        'updated_at' => $processedAt,
                    ]);

                    $wallet->increment('balance', $coinAmount);
                    $wallet->increment('total_deposited', $amount);
                }
            }
        }
        
        $this->command->info("✅ Đã tạo {$limit} giao dịch mẫu thành công!");
    }
}