<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use Exception;

use Illuminate\Support\Facades\Auth;



class ProductController extends Controller
{


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


    /**  Chỉ Admin được phép */
    private function authorizeAdmin()
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Access denied.');
        }
    }
}
