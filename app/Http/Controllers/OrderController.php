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
            return back()->with('error', '❌ Gói nạp Coinkey chỉ có thể thanh toán bằng chuyển khoản/QR.');
        }

        // Rule 2: Sản phẩm phải hỗ trợ giá Coinkey
        if (!$product->allowWalletPayment()) {
            return back()->with('error', '❌ Sản phẩm này không hỗ trợ thanh toán bằng Ví.');
        }

        // Rule 3: Giá sản phẩm phải hợp lệ
        if (!is_numeric($product->coinkey_amount) || $product->coinkey_amount <= 0) {
            return back()->with('error', '❌ Giá sản phẩm không hợp lệ.');
        }


        try {
            $wallet = $user->getOrCreateWallet();

            // 1. Tính giá sau giảm giá VIP
            $discountPercent = $wallet->discount_percent; // Lấy từ Model Attribute
            $originalPrice = $product->coinkey_amount; // Giá gốc
            $discountAmount = ($originalPrice * $discountPercent) / 100; // Tiền giảm giá
            $finalPrice = $originalPrice - $discountAmount; // Giá sau giảm

            // 2. Check số dư với giá mới
            if ($wallet->balance < $finalPrice) {
                return back()->with('error', "❌ Số dư không đủ. Giá sau giảm: " . number_format($finalPrice));
            }

            // Sử dụng transaction để đảm bảo toàn vẹn dữ liệu
            //sử dụng $trasaction thay $key
            $transaction = DB::transaction(function () use ($user, $product, $wallet, $finalPrice, $discountPercent) {


                // ✅ 1. Trừ tiền VÍ (tự động ghi log vào coinkey_transactions)
                $wallet->withdraw(
                    amount: $finalPrice,
                    type: 'purchase',
                    description: "Mua {$product->name} (Giảm {$discountPercent}%)",
                    referenceType: 'Product',
                    referenceId: $product->id
                );

                // 2. Tạo Transaction record (Lưu lịch sử đã mua bằng Coin)
                $newTransaction = Transaction::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'order_code' => (int)(now()->timestamp . rand(100, 999)), // Tạo mã đơn hàng ngẫu nhiên
                    'amount' => $finalPrice,
                    'status' => 'success',
                    'description' => "Mua {$product->name} (Giảm {$discountPercent}%)",
                    'currency' => 'COINKEY',
                    'is_processed' => true,
                    'processed_at' => now(),
                ]);

                // 3. Tạo Key
                $this->keyService->createKeyFromPackage($user, $product, $newTransaction);
                // Trả về transaction mới tạo
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
            $orderCode = (int)(now()->timestamp . rand(1000, 9999));

            // PayOS yêu cầu tối thiểu 2000 VND
            $amount = (int)max(2000, $product->price);

            // 2. Chuẩn bị data 
            $data = [
                'orderCode' => $orderCode,
                'amount'    => $amount,
                'description' => substr($product->name ?? 'Thanh toán sản phẩm', 0, 25),

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
                'description' => "Chờ thanh toán PayOS: {$product->name}",
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

            $orderCode = (int)(now()->timestamp . rand(1000, 9999));

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
     * ✅ B5: Trang Thank You - Tự động kiểm tra trạng thái thanh toán
     */
    public function thankyou(Request $request)
    {
        $orderCode = $request->query('orderCode');

        if (!$orderCode) {
            return redirect()->route('products')->with('error', '❌ Không tìm thấy mã đơn hàng');
        }

        // 1. Lấy giao dịch từ DB
        $transaction = Transaction::with(['product', 'user'])
            ->where('order_code', $orderCode)
            ->first();

        if (!$transaction) {
            return redirect()->route('products')->with('error', '❌ Giao dịch không tồn tại');
        }

        // 2.  Check PayOS status nếu vẫn đang pending
        if ($transaction->status === 'pending') {
            try {
                // Gọi sang PayOS check trạng thái thực tế
                $paymentInfo = $this->payOS->getPaymentLinkInformation($orderCode);

                if ($paymentInfo && $paymentInfo['status'] === 'PAID') {

                    // DB Transaction để đảm bảo an toàn
                    DB::transaction(function () use ($transaction) {
                        // Cập nhật trạng thái giao dịch
                        $transaction->update([
                            'status' => 'success',
                            'processed_at' => now(),
                            // Cập nhật thêm thông tin thực tế 
                            'transaction_datetime' => $paymentInfo['transactions'][0]['transactionDateTime'] ?? now(),
                        ]);

                        $product = $transaction->product;
                        $user = $transaction->user;

                        // XỬ LÝ LOGIC NẠP TIỀN 
                        if ($product->product_type === 'coinkey') {
                            $wallet = $user->getOrCreateWallet();

                            $wallet->deposit(
                                amount: $product->coinkey_amount,
                                type: 'deposit',
                                description: "Nạp {$product->coinkey_amount} Coinkey (Đơn #{$transaction->order_code})",
                                referenceType: 'Transaction',
                                referenceId: $transaction->id
                            );

                            $wallet->increment('total_deposited', $transaction->amount); // Cộng tổng nạp (để tính VIP)

                            Log::info("💰 Added {$product->coinkey_amount} coins to User {$user->id}");
                        }
                    });
                    Log::info("✅ Order {$orderCode} updated to SUCCESS via ThankYou page check.");
                } elseif ($paymentInfo['status'] === 'CANCELLED') {
                    $transaction->update(['status' => 'cancelled']);
                }
            } catch (\Exception $e) {
                Log::error("Thankyou Page Check Error: " . $e->getMessage());
            }
        }

        $product = $transaction->product;
        $user = $transaction->user;
        $key = null;

        // 3. Xử lý Key (Nếu đã thành công nhưng chưa có key, tạo ngay tại đây)
        if ($transaction->status === 'success' && $product && $product->product_type === 'package') {

            // Tìm key đã tạo (tránh tạo trùng)
            $key = \App\Models\ProductKey::where('user_id', $user->id)
                // Check key tạo sau khi transaction được khởi tạo
                ->where('created_at', '>=', $transaction->created_at)
                ->latest()
                ->first();

            // Nếu chưa có Key (do Webhook chậm hoặc chưa chạy), tạo ngay lập tức
            if (!$key) {
                try {
                    // Gọi service tạo key
                    $key = $this->keyService->createKeyFromPackage($user, $product, $transaction);
                    Log::info("🔑 Key created via ThankYou page fallback for Order {$orderCode}");
                } catch (\Exception $e) {
                    Log::error("Failed to create key on ThankYou page: " . $e->getMessage());
                }
            }
        }

        return view('thankyou', compact('transaction', 'product', 'key'));
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
