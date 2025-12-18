<?php

namespace App\Http\Controllers;

use App\Models\ProductKey;
use App\Models\Product;
use App\Models\Transaction;
use App\Services\KeyManagementService;
use App\Services\CoinkeyService;
use App\Services\PayosService;
use App\Models\KeyHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KeyManagementController extends Controller
{
    protected KeyManagementService $keyService;
    protected CoinkeyService $coinkeyService;

    public function __construct(
        KeyManagementService $keyService,
        CoinkeyService $coinkeyService
    ) {
        $this->keyService = $keyService;
        $this->coinkeyService = $coinkeyService;
    }

    /**
     * Trang quản lý danh sách Key (Dashboard chính)
     * 
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->productKeys()->with('product')->orderBy('created_at', 'desc');

        // Filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                // Tìm theo ID nếu là số
                if (is_numeric($request->search)) {
                    $q->where('id', $request->search);
                }
                $q->orWhere('key_code', 'like', '%' . $request->search . '%')
                    ->orWhere('assigned_to_email', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('email', 'like', '%' . $request->search . '%')
                            ->orWhere('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;

            if ($status === 'expired') {
                // Lọc Hết hạn: Bao gồm trạng thái 'expired' or ('active' nhưng đã quá ngày)
                $query->where(function ($q) {
                    $q->where('status', 'expired')
                        ->orWhere(function ($sub) {
                            $sub->where('status', 'active')
                                ->whereNotNull('expires_at')
                                ->where('expires_at', '<=', now());
                        });
                });
            } elseif ($status === 'active') {
                // Lọc Hoạt động: Phải là 'active' VÀ (chưa hết hạn hoặc vĩnh viễn)
                $query->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                    });
            } else {
                // Các trạng thái khác (suspended, revoked) lọc bình thường
                $query->where('status', $status);
            }
        }

        if ($request->filled('key_type')) {
            $query->where('key_type', $request->key_type);
        }

        $keys = $query->orderBy('created_at', 'desc')->paginate(20);


        // Thống kê nhanh
        $stats = [
            'total'         => $user->productKeys()->count(),
            'active'        => $user->productKeys()->active()->count(),
            'expired'       => $user->productKeys()->expired()->count(),
            'expiring_soon' => $user->productKeys()->expiringSoon(7)->count(),
            'total_spent'   => $user->productKeys()->sum('key_cost'),
        ];

        return view('keys.index', compact('keys', 'stats'));
    }

    /**
     * Chi tiết key
     */
    public function show($id)
    {
        $user = Auth::user();
        $key = ProductKey::with(['product', 'transaction', 'validationLogs'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $recentValidations = $key->validationLogs()->latest('validated_at')->limit(10)->get();

        $validationStats = [
            'total_validations' => $key->validation_count,
            'success_count'     => $key->validationLogs()->success()->count(),
            'failed_count'      => $key->validationLogs()->failed()->count(),
            'unique_ips'        => $key->validationLogs()->distinct('ip_address')->count('ip_address'),
        ];

        return view('keys.keydetails', compact('key', 'recentValidations', 'validationStats'));
    }

    /**
     * Trang xem lịch sử Key
     */
    public function history($id)
    {
        $user = Auth::user();
        $key = $user->productKeys()->findOrFail($id); // Chỉ xem key của chính mình

        // Lấy lịch sử, sắp xếp mới nhất lên đầu
        $histories = \App\Models\KeyHistory::where('product_key_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('keys.history', compact('key', 'histories'));
    }

    /**
     * Trang tạo key mới
     */
    public function create()
    {
        $products = Product::where('product_type', 'package')
            ->where('category', '!=', 'coinkey')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $wallet = Auth::user()->getOrCreateWallet();

        return view('keys.create', compact('products', 'wallet'));
    }

    /**
     * Trang xem log validation đầy đủ
     */
    public function validationLogs($id)
    {
        $user = Auth::user();
        $key = ProductKey::where('user_id', $user->id)->findOrFail($id);

        $logs = $key->validationLogs()->orderBy('validated_at', 'desc')->paginate(50);

        return view('keys.validation-logs', compact('key', 'logs'));
    }

    /* =========================================================================
     * SECTION 2: ACTION METHODS (Xử lý form, mua hàng)
     * ========================================================================= */

    /**
     * Mua gói key có sẵn (Standard Package)
     */
    public function buyPackage(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        try {
            $user = Auth::user();
            $product = Product::findOrFail($request->product_id);

            // Xử lý trừ tiền ví
            $result = $this->coinkeyService->purchaseWithCoinkey($user, $product);

            // Tạo key
            $key = $this->keyService->createKeyFromPackage($user, $product, $result['transaction']);

            return redirect()->route('keys.show', $key->id)
                ->with('success', "Key created successfully! Code: {$key->key_code}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    /**
     * Tạo key tùy chỉnh 
     * Người dùng mua gói cụ thể nhưng được đặt tên Key
     */
    public function createCustom(Request $request)
    {
        // 1. Validate
        $validated = $request->validate([
            'product_id'        => 'required|exists:products,id', // Bắt buộc phải chọn gói
            'key_code'          => 'required|string|min:3|max:50|regex:/^[A-Z0-9\-_]+$/i|unique:product_keys,key_code',
            'payment_method'    => 'required|in:wallet,cash',
        ], [
            'key_code.unique' => 'Mã key này đã tồn tại, vui lòng chọn mã khác.',
            'key_code.regex'  => 'Mã key chỉ được chứa chữ cái, số và dấu gạch ngang.',
        ]);

        $user = Auth::user();
        $product = Product::findOrFail($validated['product_id']);

        // Đảm bảo chỉ mua được gói Service (Package)
        if ($product->product_type !== 'package') {
            return back()->with('error', 'Sản phẩm này không hỗ trợ tạo key tùy chỉnh.');
        }

        try {
            $keyCode = strtoupper($validated['key_code']);
            $durationMinutes = $product->duration_minutes ?? 0; // Lấy thời gian từ gói sản phẩm

            // Tạo Order Code và Description chuẩn 
            $orderCode = (int)(now()->timestamp . rand(100, 999));
            $description = $orderCode . "K";

            // --- TRƯỜNG HỢP 1: THANH TOÁN VÍ (COINKEY) ---
            if ($request->payment_method === 'wallet') {
                $wallet = $user->getOrCreateWallet();

                // Lấy giá Coin của sản phẩm
                $costCoinkey = $product->coinkey_amount;

                if ($costCoinkey <= 0) {
                    return back()->with('error', 'Sản phẩm này không hỗ trợ thanh toán bằng Ví.');
                }

                // Kiểm tra số dư
                if ($wallet->balance < $costCoinkey) {
                    return back()->withInput()->with('error', '⛔ Số dư không đủ. Cần: ' . number_format($costCoinkey) . ' Coinkey.');
                }

                // Sử dụng DB::transaction tự động (tự commit nếu thành công, tự rollback nếu lỗi)
                $transaction = DB::transaction(function () use ($user, $product, $wallet, $costCoinkey, $keyCode, $durationMinutes, $description) {
                    // 1. Trừ tiền VÍ
                    $wallet->withdraw(
                        amount: $costCoinkey,
                        type: 'purchase',
                        description: "Mua Custom Key: {$keyCode} (Gói {$product->name})",
                        referenceType: 'Product', // Link tới Product 
                        referenceId: $product->id
                    );

                    // 2. Tạo Transaction record
                    $newTransaction = Transaction::create([
                        'user_id'     => $user->id,
                        'product_id'  => $product->id, // Link sản phẩm
                        'order_code'  => (int)(now()->timestamp . rand(100, 999)),
                        'amount'      => $costCoinkey,
                        'status'      => 'success',
                        'currency'    => 'COINKEY',
                        'description' => $description,
                        'is_processed' => true,
                        'processed_at' => now(),
                        // Thêm metadata để trang Thankyou hiển thị đúng loại giao dịch
                        'response_data' => [
                            'type' => 'custom_key_purchase',
                            'key_code' => $keyCode
                        ]
                    ]);

                    // 3. Tạo ProductKey
                    $key = ProductKey::create([
                        'user_id'           => $user->id,
                        'product_id'        => $product->id, //  Gắn ID sản phẩm
                        'transaction_id'    => $newTransaction->id,
                        'key_code'          => $keyCode,
                        'key_type'          => 'custom', // Vẫn đánh dấu là custom để biết user tự đặt tên
                        'duration_minutes'  => $durationMinutes,
                        'key_cost'          => $costCoinkey,
                        'status'            => 'active',

                    ]);

                    // 4. Kích hoạt key
                    $key->activate();

                    // Ghi lịch sử tạo Key
                    \App\Models\KeyHistory::log($key->id, 'create', "Tạo Custom Key qua Ví", [
                        'transaction_id' => $newTransaction->id,
                        'cost' => $costCoinkey . ' Coin'
                    ]);

                    return $newTransaction;
                });

                return redirect()->route('thankyou', ['orderCode' => $transaction->order_code])
                    ->with('success', "✅ Tạo key tùy chỉnh thành công! Mã key: {$keyCode}");
            }

            // --- TRƯỜNG HỢP 2: THANH TOÁN TIỀN MẶT (PAYOS) ---
            if ($request->payment_method === 'cash') {
                // Giá VND lấy từ sản phẩm
                $amountVND = $product->price;
                $orderCode = (int)(now()->timestamp . rand(100, 999));

                // Tạo transaction pending
                $transaction = Transaction::create([
                    'user_id'       => $user->id,
                    'product_id'    => $product->id,
                    'order_code'    => $orderCode,
                    'amount'        => $amountVND,
                    'status'        => 'pending',
                    'currency'      => 'VND',
                    'description'   => $description,
                    // Metadata quan trọng để Webhook/ThankYou biết mà tạo key custom
                    'response_data' => [
                        'type'             => 'custom_key_purchase',
                        'key_code'         => $keyCode,
                        'duration_minutes' => $durationMinutes,
                        'product_id'       => $product->id,

                    ]
                ]);

                // Gọi PayOS
                $payosService = app(PayosService::class);
                $paymentLink = $payosService->createPaymentLink([
                    'orderCode'   => $orderCode,
                    'amount'      => (int) $amountVND,
                    'description' => substr($description, 0, 25),
                    'returnUrl'   => route('thankyou', ['orderCode' => $orderCode]),
                    'cancelUrl'   => route('keys.index'), // Hoặc route products
                    'items'       => [[
                        'name'     => "Custom Key: {$keyCode}",
                        'quantity' => 1,
                        'price'    => (int) $amountVND
                    ]]
                ]);

                return redirect($paymentLink);
            }
        } catch (\Exception $e) {
            Log::error('Custom Key Creation Error: ' . $e->getMessage());
            return back()->withInput()->with('error', '⛔ Lỗi hệ thống: ' . $e->getMessage());
        }
    }


    public function buyCustomKey(Request $request)
    {
        $request->validate([
            'duration_minutes' => 'required|integer|min:1',
            'key_code'         => 'required|string|unique:product_keys,key_code'
        ]);

        $user = Auth::user();
        $totalCost = $request->duration_minutes * 1; // Giá 1 coin/phút

        try {
            DB::transaction(function () use ($user, $totalCost, $request) {
                $wallet = $user->getOrCreateWallet();

                // Trừ tiền thủ công
                $wallet->withdraw($totalCost, 'purchase', "Mua Custom Key {$request->duration_minutes} p", 'custom_key');

                // Tạo key
                $this->keyService->createCustomKey(
                    $user,
                    strtoupper($request->key_code),
                    $request->duration_minutes
                );
            });

            return response()->json(['success' => true, 'message' => 'Tạo key thành công!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Màn hình xác nhận gia hạn (Hiển thị thông tin gói và chọn thanh toán)
     */
    public function extendConfirm($id)
    {
        $user = Auth::user();
        $key = ProductKey::with('product')->where('user_id', $user->id)->findOrFail($id);

        if (!$key->product) {
            return back()->with('error', 'Key này là Custom Key hoặc gói sản phẩm không còn tồn tại.');
        }

        // Lấy thông tin ví để check số dư
        $wallet = $user->getOrCreateWallet();

        return view('keys.extend-confirm', compact('key', 'wallet'));
    }

    /**
     * Xử lý gia hạn (PayOS hoặc Wallet)
     */
    public function processExtension(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:wallet,cash'
        ]);

        $user = Auth::user();
        $key = ProductKey::with('product')->where('user_id', $user->id)->findOrFail($id);
        $product = $key->product;

        if (!$product) {
            return back()->with('error', 'Không tìm thấy gói sản phẩm gốc.');
        }

        try {

            // Tạo mã đơn hàng chung
            $orderCode = (int)(now()->timestamp . rand(100, 999));

            // Mô tả giao dịch có đuôi EX để phân biệt giao dịch gia hạn
            $description = $orderCode . "EX";

            // --- Phương thức 1: THANH TOÁN BẰNG VÍ ---
            if ($request->payment_method === 'wallet') {
                $costCoinkey = $product->coinkey_amount;
                $wallet = $user->getOrCreateWallet();

                if ($wallet->balance < $costCoinkey) {
                    return back()->with('error', 'Số dư ví không đủ.');
                }

                DB::transaction(function () use ($user, $key, $product, $wallet, $costCoinkey, $orderCode, $description) {
                    // 1. Trừ tiền
                    $wallet->withdraw(
                        amount: $costCoinkey,
                        type: 'purchase',
                        description: "Gia hạn Key: {$key->key_code} (+{$product->duration_minutes} phút)",
                        referenceType: 'ProductKey',
                        referenceId: $key->id
                    );

                    // 2. Tạo Transaction Log (để thống kê)
                    Transaction::create([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'order_code' => $orderCode,
                        'amount' => $costCoinkey,
                        'status' => 'success',
                        'currency' => 'COINKEY',
                        'description' => $description,
                        'is_processed' => true,
                        'processed_at' => now(),
                        'response_data' => [
                            'type' => 'key_extension', // Đánh dấu là gia hạn
                            'key_id' => $key->id,
                            'duration_minutes' => $product->duration_minutes
                        ]
                    ]);

                    // 3. Thực hiện gia hạn (Cập nhật ngày hết hạn)
                    $key->extend($product->duration_minutes);
                    $key->key_cost += $costCoinkey; // Cập nhật tổng chi phí
                    $key->status = 'active'; // Đảm bảo trạng thái active
                    $key->save();

                    //  Ghi lịch sử ngay khi thành công
                    \App\Models\KeyHistory::log($key->id, 'extend', "Gia hạn qua Ví - Đơn #{$orderCode}", [
                        'minutes_added' => $product->duration_minutes,
                        'cost' => $costCoinkey . ' Coin',
                        'new_expiry' => $key->expires_at->toDateTimeString()
                    ]);
                });
                return redirect()->route('thankyou', ['orderCode' => $orderCode])
                    ->with('success', "Gia hạn thành công! Key đã được cộng thêm thời gian.");
            }

            // --- Phương thức 2: THANH TOÁN PAYOS (TIỀN MẶT) ---
            if ($request->payment_method === 'cash') {
                $amountVND = $product->price;

                // 1. Tạo Transaction Pending
                $transaction = Transaction::create([
                    'user_id'       => $user->id,
                    'product_id'    => $product->id,
                    'order_code'    => $orderCode,
                    'amount'        => $amountVND,
                    'status'        => 'pending',
                    'currency'      => 'VND',
                    'description'   => $description,
                    // Metadata quan trọng để Webhook xử lý gia hạn
                    'response_data' => [
                        'type'             => 'key_extension',
                        'key_id'           => $key->id,
                        'key_code'         => $key->key_code,
                        'duration_minutes' => $product->duration_minutes,
                        'price_vnd'        => $amountVND // Lưu giá để cộng vào cost key
                    ]
                ]);

                // 2. Tạo Link PayOS
                $payosService = app(PayosService::class);
                $paymentLink = $payosService->createPaymentLink([
                    'orderCode'   => $orderCode,
                    'amount'      => (int) $amountVND,
                    'description' => substr($description, 0, 25),
                    'returnUrl'   => route('thankyou', ['orderCode' => $orderCode]),
                    'cancelUrl'   => route('payos.cancel-process'),
                    'items'       => [[
                        'name'     => "Extension: {$key->key_code}",
                        'quantity' => 1,
                        'price'    => (int) $amountVND
                    ]]
                ]);

                return redirect($paymentLink);
            }
        } catch (\Exception $e) {
            Log::error('Extension Error: ' . $e->getMessage());
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /* =========================================================================
     * SECTION 3: KEY MANAGEMENT ACTIONS (Gia hạn, Khóa, Mở khóa)
     * ========================================================================= */

    public function extend(Request $request, $id)
    {
        $request->validate(['additional_minutes' => 'required|integer|min:1']);
        $user = Auth::user();
        $key = ProductKey::where('user_id', $user->id)->findOrFail($id);

        try {
            $extendedKey = $this->keyService->extendKey($user, $key, $request->additional_minutes);
            return redirect()->route('keys.show', $key->id)
                ->with('success', "Gia hạn thành công! Hết hạn mới: {$extendedKey->expires_at->format('Y-m-d H:i')}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function suspend(Request $request, $id)
    {
        $key = Auth::user()->productKeys()->findOrFail($id);
        $key->suspend($request->reason);
        // Ghi log
        \App\Models\KeyHistory::log($key->id, 'suspend', "Tạm dừng: " . $request->reason);

        return back()->with('success', 'Đã tạm dừng key.');
    }

    public function activate($id)
    {
        $key = Auth::user()->productKeys()->findOrFail($id);

        if ($key->isExpired()) {
            return back()->with('error', ' Key đã hết hạn.');
        }

        $key->update(['status' => 'active']);
        // Ghi log
        \App\Models\KeyHistory::log($key->id, 'activate', "Kích hoạt lại key");

        return back()->with('success', 'Kích hoạt thành công.');
    }

    public function revoke(Request $request, $id)
    {
        $key = Auth::user()->productKeys()->findOrFail($id);
        $key->revoke($request->reason);

        \App\Models\KeyHistory::log($key->id, 'revoke', "Thu hồi key: " . $request->reason);
        return back()->with('success', 'Đã hủy key.');
    }

    /**
     * AJAX Check Key
     */
    public function checkKeyCode(Request $request)
    {
        $exists = ProductKey::where('key_code', $request->key_code)->exists();
        return response()->json([
            'available' => !$exists,
            'message'   => $exists ? 'Mã key đã tồn tại' : 'Mã key hợp lệ',
        ]);
    }
}
