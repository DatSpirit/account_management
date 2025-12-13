<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Exception;

class ProductController extends Controller
{


    /**  Danh sách sản phẩm */
    public function index()
    {
        // Chỉ lấy sản phẩm đang active (hoặc tất cả nếu là admin muốn xem)
        $products = Product::where('is_active', true)->get();

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
            'price' => 'required|numeric|min:2000',// Giá tối thiểu của PayOS là 2000
            'description' => 'nullable|string|max:1000',
            
            'product_type' => 'required|in:coinkey,package',
            'coinkey_amount' => 'required|numeric|min:0',
            'duration_minutes' => 'nullable|integer|min:0',
        ]);

         // Nếu là gói nạp tiền (coinkey) thì không cần thời hạn
        if ($validated['product_type'] === 'coinkey') {
            $validated['duration_minutes'] = null;
        }

        // Mặc định active khi tạo mới
        $validated['is_active'] = true;

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
            'price' => 'required|numeric|min:2000', // Giá tối thiểu là 2000
            'description' => 'nullable|string|max:1000',
            'product_type' => 'required|in:coinkey,package', // 'coinkey' hoặc 'package'
            'coinkey_amount' => 'required|numeric|min:0', // Admin nhập lượng coin nhận hoặc giá coin
            'duration_minutes' => 'nullable|integer|min:0', // Chỉ dùng cho 'package'
            'is_active' => 'boolean'
        ]);

        // Nếu là gói Coinkey thì duration phải bằng 0
        if ($validated['product_type'] === 'coinkey') {
            $validated['duration_minutes'] = null;
        }

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


    /**  Chỉ Admin được phép */
    private function authorizeAdmin()
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Access denied.');
        }
    }
}
