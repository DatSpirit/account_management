<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomExtensionPackage;
use App\Models\ProductKey;
use Illuminate\Http\Request;

class KeyController extends Controller
{
    /**
     * Danh sách key của user hiện tại
     */
    public function index(Request $request)
    {
        $keys = $request->user()
            ->productKeys()
            ->select('id', 'key_code', 'status', 'expires_at', 'duration_minutes', 'key_type', 'created_at') // Chọn các cột cần thiết
            ->with('product:id,name') // Chỉ lấy id và name của product
            ->orderBy('created_at', 'desc') // Sắp xếp mới nhất trước
            ->paginate(15); // Phân trang

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách key thành công',
            'data' => [
                'keys' => $keys->items(), // Chỉ lấy mảng key
                'pagination' => [
                    'current_page' => $keys->currentPage(), // Trang hiện tại
                    'total' => $keys->total(), // Tổng số key
                    'per_page' => $keys->perPage(), // Số key mỗi trang
                    'last_page' => $keys->lastPage(),
                    'next_page_url' => $keys->nextPageUrl(),
                    'prev_page_url' => $keys->previousPageUrl(),
                ]
            ]
        ]);
    }

    /**
     * Chi tiết một key (chỉ key thuộc user)
     */
    public function show(Request $request, $id)
    {
        $key = $request->user()
            ->productKeys()
            ->with('product')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết key thành công',
            'data' => $key
        ]);
    }

    /**
     * Danh sách gói gia hạn tùy chỉnh
     */
    public function extensionPackages()
    {
        $packages = CustomExtensionPackage::active()
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách gói gia hạn thành công',
            'data' => $packages
        ]);
    }

    /**
     * Gia hạn key tùy chỉnh
     */
    public function extend(Request $request, $id)
    {
        $request->validate([
            'package_id' => 'required|exists:custom_extension_packages,id',
            'payment_method' => 'required|in:wallet,cash',
        ]);
        // Request body mẫu:
        // {
        //   "package_id": 3,
        //   "payment_method": "wallet"
        // }

        $user = $request->user();
        $key = $user->productKeys()->findOrFail($id);
        $package = CustomExtensionPackage::findOrFail($request->package_id);

        // Logic giống hệt web: dùng KeyManagementService để gia hạn
        try {
            $keyService = app(\App\Services\KeyManagementService::class);

            if ($request->payment_method === 'wallet') {
                // Trừ Coinkey và gia hạn ngay
                $keyService->extendKey($user, $key, $package->duration_minutes);

                return response()->json([
                    'success' => true,
                    'message' => "Gia hạn thành công +{$package->days} ngày bằng ví Coinkey!",
                    'data' => [
                        'key' => $key->fresh(),
                        'new_expiry' => $key->expires_at?->toDateTimeString()
                    ]
                ]);
            }

            if ($request->payment_method === 'cash') {
                $amountVND = $package->price_vnd;


                $orderCode = (int)(now()->timestamp . rand(100, 999));
                $description = $orderCode . "CEX";

                // Tạo transaction pending
                $transaction = \App\Models\Transaction::create([
                    'user_id' => $user->id,
                    'product_id' => null,
                    'order_code' => $orderCode,
                    'amount' => $amountVND,
                    'status' => 'pending',
                    'currency' => 'VND',
                    'description' => $description,
                    'response_data' => [
                        'type' => 'custom_key_extension',
                        'payment_method' => 'cash',
                         // Key Details
                        'key_id' => $key->id,
                        'key_code' => $key->key_code,
                        'key_type' => $key->key_type,
                        // Package Details
                        'package_id' => $package->id,
                        'package_name' => $package->name,
                        'days_added' => $package->days,
                        'duration_minutes' => $package->duration_minutes,
                        // User Details
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        // Product Details 
                        'product_id' => $key->product_id,
                        'product_name' => $key->product?->name,
                    ]
                ]);

                // Tạo link PayOS
                $payosService = app(\App\Services\PayosService::class);
                $paymentLink = $payosService->createPaymentLink([
                    'orderCode' => $orderCode,
                    'amount' => (int) $amountVND,
                    'description' => $description,
                    'returnUrl' => route('thankyou', ['orderCode' => $orderCode]), // hoặc URL frontend của bạn
                    'cancelUrl' => route('payos.cancel-process'),
                    'items' => [[
                        'name' => "Gia hạn: {$key->key_code} (+{$package->days}d)",
                        'quantity' => 1,
                        'price' => (int) $amountVND
                    ]]
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đã tạo link thanh toán PayOS',
                    'data' => [
                        'payment_link' => $paymentLink,
                        'transaction_id' => $transaction->id,
                        'order_code' => $orderCode
                    ]
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
