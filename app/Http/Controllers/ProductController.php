<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use Exception;

use Illuminate\Support\Facades\Auth;

use App\Services\PayosService;


class ProductController extends Controller
{

    protected $payosService;

    public function __construct(PayosService $payosService)
    {
        $this->payosService = $payosService;
    }



    /**  Thanh toán PayOS + Ghi log giao dịch */
    public function pay($id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();
        $orderCode = (int) (now()->timestamp . rand(100, 999)); // Mã đơn hàng thực sự duy nhất

        // Chuẩn bị dữ liệu gửi PayOS
        $data = [
            'amount' => (int) max(1, $product->price),
            'description' => substr($product->description ?? 'Thanh toán sản phẩm', 0, 25), // Giới hạn 25 ký tự
            'orderCode' => (int) now()->format('Hisv'), // Mã đơn ngắn, không trùng
            'returnUrl' => route('products'),
            'cancelUrl' => route('products'),
            'items' => [
                [
                    'name' => $product->name,
                    'quantity' => 1,
                    'price' => (int) $product->price,
                ],
            ],
        ];

        try {
            //  Ghi log "đang khởi tạo"
            $transaction = Transaction::create([
                'user_id' => $user->id ?? null,
                'product_id' => $product->id,
                'order_code' => $orderCode,
                'amount' => $product->price,
                'status' => 'pending',
                'description' => 'Creating payment link...',
            ]);

            //  Gọi PayOS Service
            $paymentLink = $this->payosService->createPaymentLink($data);

            //  Cập nhật trạng thái thành công
            Transaction::where('order_code', $orderCode)->update([
                'status' => 'success',
                'description' => 'Payment link created successfully.',
            ]);

            return redirect($paymentLink);
        } catch (Exception $e) {

            // Cập nhật lại trạng thái giao dịch thất bại (nếu đã tồn tại)
            if (isset($transaction)) {
                $transaction->update([
                    'status' => 'failed',
                    'description' => 'Payment failed: ' . $e->getMessage(),
                ]);
            } else {
                Transaction::create([
                    'user_id' => $user->id ?? null,
                    'product_id' => $product->id,
                    'order_code' => $orderCode,
                    'amount' => $product->price,
                    'status' => 'failed',
                    'description' => 'Payment failed: ' . $e->getMessage(),
                ]);
            }

            return redirect()->route('products')->with('error', '⚠️ Payment failed. Please try again.');
        }
    }



    /**  Danh sách sản phẩm */
    public function index()
    {
        $products = Product::all();

        return view('products', [
            'products' => $products,
            'isAdmin' => Auth::check() && Auth::user()->is_admin,
        ]);
    }

    /**  Hiển thị form thêm sản phẩm (Admin Only) */
    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.products.create');
    }

    /**  Lưu sản phẩm mới */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        Product::create($validated);

        return redirect()->route('products')->with('success', '✅ Product added successfully.');
    }

    /**  Hiển thị form chỉnh sửa sản phẩm */
    public function edit(Product $product)
    {
        $this->authorizeAdmin();
        return view('admin.products.edit', compact('product'));
    }

    /**  Cập nhật sản phẩm */
    public function update(Request $request, Product $product)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
        ]);

        $product->update($validated);

        return redirect()->route('products')->with('success', '✅ Product updated successfully.');
    }

    /**  Xóa sản phẩm */
    public function destroy(Product $product)
    {
        $this->authorizeAdmin();
        $product->delete();

        return redirect()->route('products')->with('success', '🗑️ Product deleted successfully.');
    }

    /**  Trang cảm ơn */
    public function thankyou()
    {
        return view('thankyou');
    }

    /**  Chỉ Admin được phép */
    private function authorizeAdmin()
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Access denied.');
        }
    }
}
