<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Product;
use App\Models\CoinkeyWallet;
use App\Models\CustomExtensionPackage;
use Faker\Factory as Faker;
use Carbon\Carbon;

class OptimizeTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Lấy dữ liệu thực tế từ các seeder trước
        $users = User::all();
        $products = Product::all();
        $customExtensions = CustomExtensionPackage::all();

        if ($users->isEmpty() || $products->isEmpty() || $customExtensions->isEmpty()) {
            $this->command->error('⚠️ Vui lòng chạy UserSeeder, ProductSeeder, VipPackageSeeder và CustomExtensionPackageSeeder trước!');
            return;
        }

        $limit = 200; // Tạo 200 giao dịch để có nhiều ví dụ đa dạng

        foreach (range(1, $limit) as $i) {
            $user = $users->random();
            $product = $products->random();

            // Xác định loại sản phẩm
            $isTopUp = $product->product_type === 'coinkey';
            $isPackage = $product->product_type === 'package';

            // Trạng thái giao dịch - ưu tiên success để có dữ liệu key & wallet
            $status = $faker->randomElement(['success', 'success', 'success', 'success', 'pending', 'failed', 'cancelled']);
            $isSuccess = $status === 'success';

            // Thời gian ngẫu nhiên trong 10 tháng gần đây
            $createdAt = $faker->dateTimeBetween('-10 months', 'now');
            $processedAt = $isSuccess ? Carbon::parse($createdAt)->addMinutes(rand(2, 45)) : null;

            // Mã đơn hàng duy nhất
            $orderCode = (int)(microtime(true) * 1000) + $i;

            // ==================== XÁC ĐỊNH LOẠI GIAO DỊCH & HIỂN THỊ ====================
            $displayType = '';          // Lưu vào response_data.type (ưu tiên dùng ở Blade)
            $descriptionSuffix = '';    // Ký hiệu cuối description để fallback (C, K, EX, CEX)
            $description = '';
            $durationMinutes = $product->duration_minutes;

            // Biến tạm cho custom extension
            $isCustomExtend = false;
            $customPkg = null;

            // Thanh toán: VND hay Coinkey
            $paymentMethod = 'vnd';
            $currency = 'VND';
            $amount = $product->price; // Mặc định VND

            if ($isTopUp) {
                // NẠP COINKEY
                $displayType = 'coinkey_deposit';
                $description = "Nạp ví Coinkey +{$product->coinkey_amount} từ gói '{$product->name}'";
                $descriptionSuffix = ' (C)';
            } else {
                // MUA GÓI / GIA HẠN
                $wallet = CoinkeyWallet::firstOrCreate(
                    ['user_id' => $user->id],
                    ['balance' => 0, 'total_deposited' => 0, 'total_spent' => 0]
                );

                // 50% cơ hội dùng Coinkey nếu đủ tiền
                if ($wallet->balance >= $product->coinkey_amount && $faker->boolean(50)) {
                    $paymentMethod = 'coinkey';
                    $currency = 'COINKEY';
                    $amount = $product->coinkey_amount;
                }

                // Ngẫu nhiên các loại giao dịch mua/gia hạn
                $transactionScenario = $faker->randomElement([
                    'normal_purchase',      // 45% - Mua key thường
                    'custom_purchase',      // 20% - Mua custom key mới
                    'normal_extension',     // 15% - Gia hạn thường
                    'custom_extension',     // 20% - Gia hạn tùy chỉnh
                ]);

                if ($transactionScenario === 'custom_extension') {
                    $isCustomExtend = true;
                    $customPkg = $customExtensions->random();
                    $durationMinutes = $customPkg->duration_minutes;
                    $amount = $paymentMethod === 'coinkey' ? $customPkg->price_coinkey : $customPkg->price_vnd;

                    $displayType = 'custom_key_extension';
                    $description = "Gia hạn tùy chỉnh +{$customPkg->days} ngày ({$customPkg->name})";
                    $descriptionSuffix = ' (CEX)';

                } elseif ($transactionScenario === 'normal_extension') {
                    $displayType = 'key_extension';
                    $description = "Gia hạn key thường '{$product->name}'";
                    $descriptionSuffix = ' (EX)';

                } elseif ($transactionScenario === 'custom_purchase') {
                    $displayType = 'custom_key_purchase';
                    $description = "Mua Custom Key mới '{$product->name}'";
                    $descriptionSuffix = ' (K)';

                } else { // normal_purchase
                    $displayType = 'package_purchase';
                    $description = "Mua gói '{$product->name}'";
                    $descriptionSuffix = ' (K)';
                }

                $description .= ' bằng ' . ($paymentMethod === 'coinkey' ? 'Coinkey' : 'VND');
            }

            $finalDescription = $description . $descriptionSuffix;

            // Response data để lưu type và thông tin key (rất quan trọng cho Blade)
            $responseData = [
                'type' => $displayType,
                'days_added' => $isCustomExtend ? $customPkg->days : null,
                'package_name' => $isCustomExtend ? $customPkg->name : $product->name,
            ];

            // ==================== TẠO TRANSACTION ====================
            $transactionId = DB::table('transactions')->insertGetId([
                'user_id' => $user->id,
                'product_id' => $isCustomExtend ? null : $product->id,
                'order_code' => $orderCode,
                'amount' => $amount,
                'currency' => $currency,
                'status' => $status,
                'description' => $finalDescription,
                'is_processed' => $isSuccess,
                'processed_at' => $processedAt,
                'payment_link_id' => $currency === 'VND' ? 'PL' . Str::random(10) : null,
                'account_number' => $currency === 'VND' ? '0333' . $faker->numberBetween(100000, 999999) : null,
                'response_data' => json_encode($responseData),
                'created_at' => $createdAt,
                'updated_at' => $processedAt ?? $createdAt,
            ]);

            // ==================== XỬ LÝ KHI SUCCESS ====================
            if ($isSuccess) {
                $wallet = CoinkeyWallet::firstOrCreate(
                    ['user_id' => $user->id],
                    ['balance' => 0, 'total_deposited' => 0, 'total_spent' => 0]
                );
                $currentBalance = $wallet->balance ?? 0;

                // 1. Nạp Coinkey
                if ($isTopUp) {
                    $coinAmount = $product->coinkey_amount;
                    DB::table('coinkey_transactions')->insert([
                        'wallet_id' => $wallet->id,
                        'user_id' => $user->id,
                        'type' => 'deposit',
                        'amount' => $coinAmount,
                        'balance_before' => $currentBalance,
                        'balance_after' => $currentBalance + $coinAmount,
                        'description' => "Nạp từ đơn #{$orderCode}",
                        'reference_type' => 'Transaction',
                        'reference_id' => $transactionId,
                        'created_at' => $processedAt,
                        'updated_at' => $processedAt,
                    ]);

                    $wallet->increment('balance', $coinAmount);
                    $wallet->increment('total_deposited', $product->price);
                }

                // 2. Trừ Coinkey nếu thanh toán bằng ví
                if ($paymentMethod === 'coinkey' && !$isTopUp) {
                    DB::table('coinkey_transactions')->insert([
                        'wallet_id' => $wallet->id,
                        'user_id' => $user->id,
                        'type' => 'purchase',
                        'amount' => -$amount,
                        'balance_before' => $currentBalance,
                        'balance_after' => $currentBalance - $amount,
                        'description' => $finalDescription,
                        'reference_type' => 'Transaction',
                        'reference_id' => $transactionId,
                        'created_at' => $processedAt,
                        'updated_at' => $processedAt,
                    ]);

                    $wallet->decrement('balance', $amount);
                    $wallet->increment('total_spent', $amount);
                }

                // 3. Tạo Product Key nếu là mua gói hoặc gia hạn
                if ($isPackage || $isCustomExtend) {
                    $keyCode = strtoupper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4));

                    DB::table('product_keys')->insert([
                        'user_id' => $user->id,
                        'product_id' => $isCustomExtend ? null : $product->id,
                        'transaction_id' => $transactionId,
                        'key_code' => $keyCode,
                        'key_type' => $transactionScenario === 'custom_purchase' ? 'custom' : 'auto_generated',
                        'duration_minutes' => $durationMinutes,
                        'key_cost' => $amount,
                        'status' => 'active',
                        'activated_at' => $processedAt,
                        'expires_at' => Carbon::parse($processedAt)->addMinutes($durationMinutes),
                        'created_at' => $processedAt,
                        'updated_at' => $processedAt,
                    ]);

                    $keyId = DB::getPdo()->lastInsertId();

                    // Cập nhật lại response_data với key_id và key_code thực tế
                    DB::table('transactions')
                        ->where('id', $transactionId)
                        ->update([
                            'response_data' => json_encode(array_merge($responseData, [
                                'key_id' => $keyId,
                                'key_code' => $keyCode,
                            ]))
                        ]);

                    // Tăng sold_count nếu không phải gia hạn custom
                    if (!$isCustomExtend && $product->exists) {
                        $product->increment('sold_count');
                    }
                }
            }
        }

        $this->command->info("✅ Đã tạo thành công {$limit} giao dịch mẫu đa dạng!");
        $this->command->info("   • Nạp Coinkey (C)");
        $this->command->info("   • Mua key thường / Custom Key (K)");
        $this->command->info("   • Gia hạn thường (EX)");
        $this->command->info("   • Gia hạn tùy chỉnh (CEX)");
        $this->command->info("   • Thanh toán VND & Coinkey, success/failed/pending");
    }
}