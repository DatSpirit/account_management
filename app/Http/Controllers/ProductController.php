<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PayosService;

class ProductController extends Controller
{
    // Danh sách sản phẩm mẫu
    private function getProducts()
    {
        return [
            // Laptops
            ['id' => 1, 'name' => 'MacBook Pro 14" M3', 'price' => 45990000, 'category' => 'laptop', 'description' => 'Chip M3 mạnh mẽ, màn hình Liquid Retina XDR 14 inch, RAM 16GB, SSD 512GB'],
            ['id' => 2, 'name' => 'Dell XPS 15', 'price' => 35990000, 'category' => 'laptop', 'description' => 'Intel Core i7, RAM 16GB, SSD 512GB, màn hình 15.6" 4K OLED'],
            ['id' => 3, 'name' => 'ASUS ROG Zephyrus G14', 'price' => 32990000, 'category' => 'laptop', 'description' => 'AMD Ryzen 9, RTX 4060, RAM 16GB, màn hình 14" 165Hz'],
            ['id' => 4, 'name' => 'Lenovo ThinkPad X1 Carbon', 'price' => 38990000, 'category' => 'laptop', 'description' => 'Intel Core i7, RAM 16GB, SSD 512GB, siêu nhẹ 1.12kg'],
            
            // Phones
            ['id' => 5, 'name' => 'iPhone 15 Pro Max', 'price' => 34990000, 'category' => 'phone', 'description' => 'Chip A17 Pro, Titanium, Camera 48MP, màn hình 6.7"'],
            ['id' => 6, 'name' => 'Samsung Galaxy S24 Ultra', 'price' => 32990000, 'category' => 'phone', 'description' => 'Snapdragon 8 Gen 3, Camera 200MP, S-Pen, RAM 12GB'],
            ['id' => 7, 'name' => 'iPhone 15', 'price' => 22990000, 'category' => 'phone', 'description' => 'Chip A16 Bionic, Dynamic Island, Camera 48MP'],
            ['id' => 8, 'name' => 'Google Pixel 8 Pro', 'price' => 24990000, 'category' => 'phone', 'description' => 'Google Tensor G3, AI Camera, màn hình 6.7" 120Hz'],
            
            // Tablets
            ['id' => 9, 'name' => 'iPad Pro 11" M2', 'price' => 25990000, 'category' => 'tablet', 'description' => 'Chip M2, màn hình Liquid Retina, hỗ trợ Apple Pencil'],
            ['id' => 10, 'name' => 'iPad Air 5', 'price' => 17990000, 'category' => 'tablet', 'description' => 'Chip M1, màn hình 10.9", hỗ trợ Magic Keyboard'],
            ['id' => 11, 'name' => 'Samsung Galaxy Tab S9', 'price' => 19990000, 'category' => 'tablet', 'description' => 'Snapdragon 8 Gen 2, màn hình 11" AMOLED, S-Pen'],
            ['id' => 12, 'name' => 'Microsoft Surface Pro 9', 'price' => 26990000, 'category' => 'tablet', 'description' => 'Intel Core i5, RAM 8GB, màn hình 13" PixelSense'],
            
            // Accessories
            ['id' => 13, 'name' => 'AirPods Pro Gen 2', 'price' => 6490000, 'category' => 'accessories', 'description' => 'Chống ồn chủ động, sạc USB-C, độ bền pin 6 giờ'],
            ['id' => 14, 'name' => 'Sony WH-1000XM5', 'price' => 8990000, 'category' => 'accessories', 'description' => 'Tai nghe over-ear, chống ồn hàng đầu, pin 30 giờ'],
            ['id' => 15, 'name' => 'Apple Watch Series 9', 'price' => 10990000, 'category' => 'accessories', 'description' => 'GPS + Cellular, màn hình Always-On, theo dõi sức khỏe'],
            ['id' => 16, 'name' => 'Magic Keyboard cho iPad', 'price' => 8490000, 'category' => 'accessories', 'description' => 'Bàn phím backlit, trackpad, góc nhìn linh hoạt'],
            
            // Gaming
            ['id' => 17, 'name' => 'PlayStation 5', 'price' => 13990000, 'category' => 'gaming', 'description' => 'Console gaming thế hệ mới, SSD 825GB, 4K 120fps'],
            ['id' => 18, 'name' => 'Xbox Series X', 'price' => 13990000, 'category' => 'gaming', 'description' => 'Console 4K, SSD 1TB, Game Pass Ultimate'],
            ['id' => 19, 'name' => 'Nintendo Switch OLED', 'price' => 8990000, 'category' => 'gaming', 'description' => 'Màn hình OLED 7", chơi cầm tay và TV'],
            ['id' => 20, 'name' => 'Logitech G Pro X Superlight', 'price' => 3490000, 'category' => 'gaming', 'description' => 'Chuột gaming không dây, 63g, cảm biến Hero 25K'],
            ['id' => 21, 'name' => 'Razer BlackWidow V4 Pro', 'price' => 5990000, 'category' => 'gaming', 'description' => 'Bàn phím cơ gaming, RGB, switch Green'],
            ['id' => 22, 'name' => 'SteelSeries Arctis Nova Pro', 'price' => 7990000, 'category' => 'gaming', 'description' => 'Tai nghe gaming cao cấp, DAC riêng, mic AI'],
        ];
    }

    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = $this->getProducts();
        return view('products', compact('products'));
    }

    // Tạo link thanh toán
    public function pay($id, PayosService $payos)
    {
        $products = collect($this->getProducts())->keyBy('id')->toArray();

        if (!isset($products[$id])) {
            abort(404, 'Sản phẩm không tồn tại');
        }

        $product = $products[$id];

        $data = [
            'orderCode'   => 'ORDER-' . time() . '-' . $id,
            'amount'      => $product['price'],
            'description' => 'Thanh toán: ' . $product['name'],
            'returnUrl'   => route('thankyou'),
            'cancelUrl'   => route('products'),
        ];

        try {
            $resp = $payos->createPaymentLink($data);
            return redirect($resp['checkoutUrl']);
        } catch (\Throwable $e) {
            return back()->with('error', 'Lỗi thanh toán: ' . $e->getMessage());
        }
    }

    // Trang cảm ơn sau thanh toán
    public function thankyou()
    {
        return view('thankyou');
    }
}