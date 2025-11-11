<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\PayosService;
use PayOS\PayOS;
use Exception;

class OrderController extends Controller
{
    protected $payOS;
    protected $payosService;

    public function __construct(PayosService $payosService)
    {
        $this->payosService = $payosService;

        // Khởi tạo PayOS SDK
        $this->payOS = new PayOS(
            env('PAYOS_CLIENT_ID'),
            env('PAYOS_API_KEY'),
            env('PAYOS_CHECKSUM_KEY')
        );
    }

    /**
     * B1: Người dùng chọn sản phẩm → tạo giao dịch → tạo link thanh toán PayOS
     */
    public function pay($id)
    {
        try {
            $product = Product::findOrFail($id);
            $user = Auth::user();
            
            // Tạo orderCode unique
            $orderCode = (int)(now()->timestamp . rand(1000, 9999));

            // Đảm bảo amount >= 2000 VND (yêu cầu của PayOS)
            $amount = (int)max(2000, $product->price);

            $data = [
                'amount' => $amount,
                'description' => substr($product->name ?? 'Thanh toán sản phẩm', 0, 25),
                'orderCode' => $orderCode,



                'returnUrl' => route('thankyou', ['orderCode' => $orderCode], true),
                'cancelUrl' => route('payos.cancel-process', [], true),
                'items' => [
                    [
                        'name' => substr($product->name, 0, 30),
                        'quantity' => 1,
                        'price' => $amount,
                    ],
                ],
            ];

            // Tạo bản ghi giao dịch ở trạng thái "pending"
            $transaction = Transaction::create([
                'user_id' => $user->id ?? null,
                'product_id' => $product->id,
                'order_code' => $orderCode,
                'amount' => $amount,
                'status' => 'pending',
                'description' => 'Awaiting payment confirmation...',
            ]);

            Log::info("💳 Creating payment for order #{$orderCode}", [
                'user_id' => $user->id ?? 'guest',
                'product_id' => $product->id,
                'amount' => $amount
            ]);

            // Gọi PayOS Service để tạo link thanh toán
            $paymentLink = $this->payosService->createPaymentLink($data);

            Log::info("✅ Payment link created successfully", [
                'orderCode' => $orderCode,
                'link' => $paymentLink
            ]);

            // Chuyển hướng người dùng đến trang thanh toán PayOS
            return redirect($paymentLink);

        } catch (Exception $e) {
            Log::error('❌ Payment failed: ' . $e->getMessage(), [
                'product_id' => $id,
                'user_id' => Auth::id()
            ]);

            // Cập nhật trạng thái giao dịch thất bại (nếu đã tạo)
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
     * ✅ B5: Trang cảm ơn sau khi thanh toán xong
     */
    public function thankyou(Request $request)
    {
        $orderCode = $request->query('orderCode');
        $transaction = null;

        if ($orderCode) {
            $transaction = Transaction::where('order_code', $orderCode)->first();
            
            Log::info("👤 User viewing thank you page", [
                'orderCode' => $orderCode,
                'status' => $transaction->status ?? 'not_found'
            ]);
        }

        return view('thankyou', [
            'transaction' => $transaction,
            'orderCode' => $orderCode
        ]);
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