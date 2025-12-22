<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Services\PayosService;
use App\Services\KeyManagementService;
use App\Services\CoinkeyService;
use PayOS\PayOS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    protected $payOS;
    protected $payosService;
    protected $keyService;
    protected $coinkeyService;

    public function __construct(
        PayosService $payosService,
        KeyManagementService $keyService,
        CoinkeyService $coinkeyService
    ) {
        $this->payosService = $payosService;
        $this->keyService = $keyService;
        $this->coinkeyService = $coinkeyService;

        // Khởi tạo PayOS SDK
        $this->payOS = new PayOS(
            env('PAYOS_CLIENT_ID'),
            env('PAYOS_API_KEY'),
            env('PAYOS_CHECKSUM_KEY')
        );
    }
    // CẦU NỐI (Public - Nhận request từ Route)
    public function payRoute($id)
    {
        // 1. Kiểm tra sản phẩm có tồn tại không
        $product = Product::findOrFail($id);

        // 2. Lấy user hiện tại
        $user = Auth::user();

        // 3. Gọi hàm xử lý logic chính
        return $this->pay($user, $product);
    }


    /**
     * Xử lý mua hàng trung tâm (Nhận request từ Modal)
     */
    public function process(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'payment_method' => 'required|in:cash,wallet'
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($request->product_id);

        // --- NHÁNH 1: THANH TOÁN BẰNG VÍ COINKEY ---
        if ($request->payment_method === 'wallet') {
            return $this->processWalletPayment($user, $product);
        }

        // --- NHÁNH 2: THANH TOÁN TIỀN MẶT (PAYOS) ---
        if ($request->payment_method === 'cash') {
            return $this->Pay($user, $product);
        }
    }

    /**
     * Logic xử lý thanh toán ví 
     */
    private function processWalletPayment($user, $product)
    {
        // Rule 1: Không cho mua Coinkey bằng Coinkey
        if ($product->isCoinkeyPack()) {
            return back()->with('error', '⌚  Gói nạp Coinkey chỉ có thể thanh toán bằng chuyển khoản/QR.');
        }

        // Rule 2: Sản phẩm phải hỗ trợ giá Coinkey
        if (!$product->allowWalletPayment()) {
            return back()->with('error', '⌚  Sản phẩm này không hỗ trợ thanh toán bằng Ví.');
        }

        // Rule 3: Giá sản phẩm phải hợp lệ
        if (!is_numeric($product->coinkey_amount) || $product->coinkey_amount <= 0) {
            return back()->with('error', '⌚  Giá sản phẩm không hợp lệ.');
        }


        try {
            $wallet = $user->getOrCreateWallet();

            // 1. Tính giá sau giảm giá VIP
            $discountPercent = $wallet->discount_percent; // Lấy từ Model Attribute
            $originalPrice = $product->coinkey_amount; // Giá gốc
            $discountAmount = ($originalPrice * $discountPercent) / 100; // Tiền giảm giá
            $finalPrice = $originalPrice - $discountAmount; // Giá sau giảm giá

            // 2. Check số dư với giá mới
            if ($wallet->balance < $finalPrice) {
                return back()->with('error', "⌚ Số dư không đủ. Giá sau giảm: " . number_format($finalPrice));
            }

            // Sử dụng transaction để đảm bảo toàn vẹn dữ liệu
            //sử dụng $trasaction thay $key
            $transaction = DB::transaction(function () use ($user, $product, $wallet, $finalPrice, $discountPercent, $originalPrice) {

                $orderCode = (int)(now()->timestamp . rand(100, 999)); // Tạo mã đơn hàng ngẫu nhiên
                // 1. Trừ tiền VÍ (tự động ghi log vào coinkey_transactions)
                $wallet->withdraw(
                    amount: $finalPrice,
                    type: 'purchase',
                    description: "Mua {$product->name} (Giảm {$discountPercent}%)",
                    referenceType: 'Product',
                    referenceId: $product->id
                );

                // 2. Tạo Transaction record với METADATA ĐẦY ĐỦ
                $newTransaction = Transaction::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'order_code' => $orderCode,
                    'amount' => $finalPrice,
                    'status' => 'success',
                    'description' => $orderCode . "K", // Ký hiệu K = mua Key/package
                    'currency' => 'COINKEY',
                    'is_processed' => true,
                    'processed_at' => now(),
                    // METADATA CHI TIẾT VỀ GIAO DỊCH
                    'response_data' => [
                        'type' => 'package_purchase', // Đánh dấu mua gói key
                        'payment_method' => 'wallet',
                        'original_price' => $originalPrice,
                        'discount_percent' => $discountPercent,
                        'final_price' => $finalPrice,
                        'duration_minutes' => $product->duration_minutes,
                    ]
                ]);

                // 3. Tạo Key
                $key = $this->keyService->createKeyFromPackage($user, $product, $newTransaction);
                // 4. CẬP NHẬT key_id vào transaction
                $newTransaction->update([
                    'response_data' => array_merge($newTransaction->response_data ?? [], [
                        'key_id' => $key->id,
                        'key_code' => $key->key_code,
                    ])
                ]);

                // 5. GHI LOG LỊCH SỬ KEY 
                \App\Models\KeyHistory::log($key->id, 'create', "Tạo Key qua Ví - Order Code: {$newTransaction->order_code}", [
                    'Key_Code' => $key->key_code,
                    'cost' => $finalPrice . ' Coin',
                    'duration_minutes' => $product->duration_minutes,
                    'discount_applied' => $discountPercent . '%'
                ]);

                return $newTransaction;
            });

            return redirect()->route('thankyou', ['orderCode' => $transaction->order_code])
                ->with('success', "✅ Mua thành công! Bạn tiết kiệm được " . number_format($discountAmount) . " Coin.");
        } catch (\Exception $e) {
            Log::error('Wallet Payment Error: ' . $e->getMessage());
            return back()->with('error', '❌ Lỗi thanh toán ví: ' . $e->getMessage());
        }
    }
    /**
     * Logic thanh toán bằng PayOS (Tiền mặt)
     */
    public function pay($user, $product)
    {
        try {
            // $product = Product::findOrFail($id);
            // $user = Auth::user();

            // 1. Tạo mã đơn hàng unique
            $orderCode = (int)(now()->timestamp . rand(100, 999));

            // PayOS yêu cầu tối thiểu 2000 VND
            $amount = (int)max(2000, $product->price);

            // 2.Xác định suffix cho description dựa vào product_type
            $productType = $product->product_type ?? '';
            $suffix = $productType === 'package' ? 'K' : ($productType === 'coinkey' ? 'C' : '');
            $description = $orderCode . $suffix;

            // 3. Chuẩn bị data 
            $data = [
                'orderCode' => $orderCode,
                'amount'    => $amount,
                'description' => $description,

                // return + cancel 
                'returnUrl' => route('thankyou', ['orderCode' => $orderCode], true),
                'cancelUrl' => route('payos.cancel-process', [], true),

                'items' => [
                    [
                        'name' => substr($product->name, 0, 30),
                        'quantity' => 1,
                        'price' => $amount,
                    ]
                ]
            ];

            // 3. Lưu Transaction
            $transaction = Transaction::create([
                'user_id' => $user->id ?? null,
                'product_id' => $product->id,
                'order_code' => $orderCode,
                'amount' => $amount,
                'status' => 'pending',
                'description' => $description,
                'currency' => 'VND',
                'is_processed' => false,
            ]);

            Log::info("💳 Creating payment for order #{$orderCode}", [
                'user_id' => $user->id,
                'product_id' => $product->id,
                'amount' => $amount
            ]);

            // 4. Tạo link thanh toán PayOS
            $paymentLink = $this->payosService->createPaymentLink($data);

            Log::info("✅ Payment link created successfully", [
                'orderCode' => $orderCode,
                'link' => $paymentLink
            ]);

            // 5. Redirect đến PayOS
            return redirect($paymentLink);
        } catch (Exception $e) {
            Log::error('❌ Payment failed: ' . $e->getMessage(), [
                'product_id' => $product->id ?? 'unknown',
                'user_id'    => $user->id ?? 'unknown'
            ]);

            if (isset($transaction)) {
                $transaction->update([
                    'status' => 'failed',
                    'description' => 'Payment link creation failed: ' . $e->getMessage(),
                ]);
            }

            return redirect()->route('products')
                ->with('error', '⚠️ Không thể tạo link thanh toán. Vui lòng thử lại.');
        }
    }

    /**
     * ✅ B2: API tạo đơn hàng (nếu bạn muốn gọi AJAX)
     */
    public function createOrder(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|integer|min:2000',
                'description' => 'required|string|max:25',
                'returnUrl' => 'nullable|url',
                'cancelUrl' => 'nullable|url',
            ]);

            $orderCode = (int)(now()->timestamp . rand(100, 999));

            $body = [
                'amount' => $validated['amount'],
                'description' => $validated['description'],
                'orderCode' => $orderCode,
                'returnUrl' => $validated['returnUrl'] ?? route('thankyou'),
                'cancelUrl' => $validated['cancelUrl'] ?? route('products'),
                'items' => [
                    [
                        'name' => $validated['description'],
                        'quantity' => 1,
                        'price' => $validated['amount'],
                    ],
                ],
            ];

            $response = $this->payOS->createPaymentLink($body);

            Log::info("📝 Order created via API", ['orderCode' => $orderCode]);

            return response()->json([
                'error' => 0,
                'message' => 'Success',
                'data' => [
                    'checkoutUrl' => $response['checkoutUrl'],
                    'orderCode' => $orderCode
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 1,
                'message' => 'Validation failed',
                'details' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * ✅ B3: Lấy thông tin link thanh toán
     */
    public function getPaymentLinkInfoOfOrder(string $orderCode)
    {
        try {
            $response = $this->payOS->getPaymentLinkInformation($orderCode);

            return response()->json([
                'error' => 0,
                'message' => 'Success',
                'data' => $response
            ]);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * 🚫 B4: PayOS gọi về khi user bấm HỦY trên trang thanh toán
     */
    public function cancelPayment(Request $request)
    {
        try {
            $cancelBody = $request->all();
            $orderCode = $cancelBody['orderCode'] ?? null;

            $transaction = Transaction::where('order_code', $orderCode)->first();

            if ($transaction) {
                // Cập nhật trạng thái giao dịch thành 'cancelled' 
                $transaction->update(['status' => 'cancelled']);

                Log::warning("🚫 Order {$orderCode} status updated to CANCELLED by user.");

                // Chuyển hướng hoặc trả về View thông báo hủy
                return redirect()->route('pay.cancel-page', ['orderCode' => $orderCode])
                    ->with('message', 'Giao dịch đã bị hủy thành công.');
            }

            // Nếu không tìm thấy transaction hoặc không có orderCode
            return redirect()->route('products')->with('error', 'Không tìm thấy giao dịch.');
        } catch (Exception $e) {
            // ... xử lý lỗi ...
            return redirect()->route('products')->with('error', 'Đã xảy ra lỗi hệ thống.');
        }
    }

    /**
     *  B5: Trang Thank You - Tự động kiểm tra trạng thái thanh toán
     */
    public function thankyou(Request $request)
    {
        $orderCode = $request->query('orderCode');

        if (!$orderCode) {
            return redirect()->route('products')->with('error', '⌚ Không tìm thấy mã đơn hàng');
        }

        // 1. LOAD TRANSACTION với FULL RELATIONS
        $transaction = Transaction::with(['product', 'user', 'productKey'])
            ->where('order_code', $orderCode)
            ->first();

        if (!$transaction) {
            return redirect()->route('products')->with('error', '⌚ Giao dịch không tồn tại');
        }

        // 2. CHECK PayOS STATUS nếu còn PENDING
        if ($transaction->status === 'pending') {
            try {
                $paymentInfo = $this->payOS->getPaymentLinkInformation($orderCode);

                if ($paymentInfo && $paymentInfo['status'] === 'PAID') {
                    DB::transaction(function () use ($transaction, $paymentInfo) {
                        $transaction->update([
                            'status' => 'success',
                            'processed_at' => now(),
                            'transaction_datetime' => $paymentInfo['transactions'][0]['transactionDateTime'] ?? now(),
                        ]);

                        // XỬ LÝ FULFILLMENT
                        $this->fulfillOrder($transaction);
                    });

                    Log::info("✅ Order {$orderCode} updated to SUCCESS via ThankYou page check.");
                } elseif ($paymentInfo['status'] === 'CANCELLED') {
                    $transaction->update(['status' => 'cancelled']);
                }
            } catch (\Exception $e) {
                Log::error("Thankyou Page Check Error: " . $e->getMessage());
            }
        }

        // 3. REFRESH TRANSACTION để lấy data mới nhất
        $transaction->refresh();

        // 4. EXTRACT METADATA (core data source)
        $meta = $transaction->response_data ?? [];
        $type = $meta['type'] ?? null;

        // 5. BUILD VIEW DATA từ METADATA (không phụ thuộc product relation)
        $viewData = [
            'transaction' => $transaction,
            'meta' => $meta,
            'type' => $type,

            // Virtual Relations từ metadata
            'key' => null,
            'product' => $transaction->product, // Có thể null với custom extension
            'user' => $transaction->user,

            // Extension-specific data từ metadata
            'package_name' => $meta['package_name'] ?? null,
            'days_added' => $meta['days_added'] ?? null,
            'key_code' => $meta['key_code'] ?? null,
            'key_id' => $meta['key_id'] ?? null,
            'duration_minutes' => $meta['duration_minutes'] ?? null,

            // Display flags
            'is_custom_extension' => $type === 'custom_key_extension',
            'is_key_extension' => in_array($type, ['key_extension', 'custom_key_extension']),
            'is_custom_key_purchase' => $type === 'custom_key_purchase',
            'is_package_purchase' => $type === 'package_purchase',
            'is_coinkey_deposit' => $transaction->product?->isCoinkeyPack() ?? false,
        ];

        // 6. LOAD KEY nếu có key_id trong metadata hoặc relation
        if (!empty($meta['key_id'])) {
            $viewData['key'] = \App\Models\ProductKey::with(['product', 'user'])
                ->find($meta['key_id']);
        } elseif ($transaction->productKey) {
            $viewData['key'] = $transaction->productKey;
        }

        // 7. FALLBACK: Tìm key mới tạo gần đây (cho package purchase)
        if (!$viewData['key'] && $viewData['is_package_purchase'] && $transaction->status === 'success') {
            $viewData['key'] = \App\Models\ProductKey::where('user_id', $transaction->user_id)
                ->where('created_at', '>=', $transaction->created_at)
                ->latest()
                ->first();
        }

        return view('thankyou', $viewData);
    }

    /**
     * Xử lý giao hàng sau khi thanh toán thành công
     */
    private function fulfillOrder(Transaction $transaction)
    {
        try {
            $user = $transaction->user;
            $product = $transaction->product;
            $meta = $transaction->response_data ?? [];
            $type = $meta['type'] ?? null;

            Log::info(" Processing fulfillment for order {$transaction->order_code}", [
                'type' => $type,
                'user_id' => $user->id ?? 'N/A'
            ]);

            // 1️ XỬ LÝ GIA HẠN TÙY CHỈNH (Custom Extension)
            if ($type === 'custom_key_extension') {
                $keyId = $meta['key_id'] ?? null;
                $duration = $meta['duration_minutes'] ?? 0;

                if (!$keyId || !$duration) {
                    Log::error(" Missing key_id or duration for custom extension");
                    return;
                }

                $key = \App\Models\ProductKey::with(['product', 'user'])->find($keyId);
                if (!$key) {
                    Log::error(" Key not found: {$keyId}");
                    return;
                }

                $oldExpiry = $key->expires_at?->toDateTimeString() ?? 'N/A';
                $key->extend($duration);
                $key->status = 'active';

                if ($transaction->currency === 'VND') {
                    $key->key_cost += ($transaction->amount / 1000);
                }
                $key->save();

                // Cập nhật metadata
                $transaction->update([
                    'response_data' => array_merge($meta, [
                        'actual_new_expiry' => $key->expires_at->toIso8601String(),
                    ])
                ]);

                \App\Models\KeyHistory::log($key->id, 'custom_extend', "Gia hạn tùy chỉnh - Order #{$transaction->order_code}", [
                    'package_name' => $meta['package_name'] ?? 'N/A',
                    'days_added' => $meta['days_added'] ?? 0,
                    'old_expiry' => $oldExpiry,
                    'new_expiry' => $key->expires_at->toDateTimeString()
                ]);

                Log::info(" Custom extension completed");
                return;
            }

            // 2️ XỬ LÝ GIA HẠN THƯỜNG (Key Extension)
            if ($type === 'key_extension') {
                $keyId = $meta['key_id'] ?? null;
                $duration = $meta['duration_minutes'] ?? 0;

                if (!$keyId || !$duration) return;

                $key = \App\Models\ProductKey::find($keyId);
                if (!$key) return;

                $oldExpiry = $key->expires_at?->toDateTimeString() ?? 'N/A';
                $key->extend($duration);
                $key->status = 'active';

                if ($transaction->currency === 'VND') {
                    $key->key_cost += ($transaction->amount / 1000);
                }
                $key->save();

                \App\Models\KeyHistory::log($key->id, 'extend', "Gia hạn - Order #{$transaction->order_code}", [
                    'minutes_added' => $duration,
                    'old_expiry' => $oldExpiry,
                    'new_expiry' => $key->expires_at->toDateTimeString()
                ]);

                Log::info(" Extension completed");
                return;
            }

            // 3️ XỬ LÝ MUA CUSTOM KEY
            if ($type === 'custom_key_purchase') {
                $keyService = app(\App\Services\KeyManagementService::class);

                $newKey = $keyService->createCustomKey(
                    user: $user,
                    customKeyCode: $meta['key_code'],
                    durationMinutes: $meta['duration_minutes'],
                    baseProduct: $product
                );

                $transaction->update([
                    'response_data' => array_merge($meta, [
                        'key_id' => $newKey->id,
                    ])
                ]);

                $newKey->update(['transaction_id' => $transaction->id]);

                \App\Models\KeyHistory::log($newKey->id, 'create', "Tạo Custom Key - Order #{$transaction->order_code}");

                Log::info(" Custom key created");
                return;
            }

            // 4️ XỬ LÝ NẠP COINKEY
            if ($product?->isCoinkeyPack()) {
                $wallet = $user->getOrCreateWallet();
                $wallet->deposit(
                    amount: $product->coinkey_amount,
                    type: 'deposit',
                    description: "Nạp {$product->coinkey_amount} Coinkey - Order #{$transaction->order_code}",
                    referenceType: 'Transaction',
                    referenceId: $transaction->id
                );

                Log::info("✅ Coinkey deposited");
                return;
            }

            // 5️ XỬ LÝ MUA GÓI KEY THƯỜNG
            if ($product?->isServicePackage()) {
                $keyService = app(\App\Services\KeyManagementService::class);
                $key = $keyService->createKeyFromPackage($user, $product, $transaction);

                if ($key) {
                    $transaction->update([
                        'response_data' => array_merge($meta, [
                            'type' => 'package_purchase',
                            'key_id' => $key->id,
                            'key_code' => $key->key_code,
                        ])
                    ]);

                    \App\Models\KeyHistory::log($key->id, 'create', "Mua gói {$product->name}");
                    Log::info("✅ Package key created");
                }
            }
        } catch (\Exception $e) {
            Log::error("❌ Fulfillment Error: " . $e->getMessage(), [
                'order_code' => $transaction->order_code,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    /**
     * ⚙️ Xử lý lỗi chung
     */
    private function handleException(Exception $e)
    {
        Log::error('❌ PayOS Error: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);

        return response()->json([
            'error' => 1,
            'message' => 'Error occurred',
            'details' => $e->getMessage(),
        ], 500);
    }
}
