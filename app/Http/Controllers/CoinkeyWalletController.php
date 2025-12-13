<?php

namespace App\Http\Controllers;

use App\Services\CoinkeyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class CoinkeyWalletController extends Controller
{
    protected CoinkeyService $coinkeyService;

    public function __construct(CoinkeyService $coinkeyService)
    {
        $this->coinkeyService = $coinkeyService;
    }

    /**
     * Hiển thị trang ví Coinkey
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();

        // Lịch sử biến động số dư coin
        $walletTransactions = $wallet->transactions()->latest()->paginate(10, ['*'], 'wallet_page');

        // Lịch sử đơn hàng (Nạp tiền thật / PayOS)
        $transactions = $user->transactions()->latest()->paginate(10, ['*'], 'order_page');

        // Thống kê
        $stats = [
            'current_balance' => $wallet->balance,
            'total_deposited' => $wallet->total_deposited,
            'total_spent' => $wallet->total_spent,
            'transaction_count' => $wallet->transactions()->count(),
            'deposits_count' => $wallet->transactions()->where('type', 'deposit')->count(),
            'purchases_count' => $wallet->transactions()->where('type', 'purchase')->count(),
            'refunds_count' => $wallet->transactions()->where('type', 'refund')->count(),
        ];

        return view('wallet.index', compact('wallet', 'transactions', 'stats', 'walletTransactions'));
    }

    /**
     * Trang mua gói Coinkey với hệ thống VIP & ưu đãi
     */
    public function buyPackage(Request $request)
    {
        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();
        $vipLevel = $wallet->vip_level;

        // Lấy RIÊNG các gói ưu đãi (category = 'vip_package')
        $products = Product::where('product_type', 'package')
            ->where('category', 'vip_package')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();

        // Xử lý từng gói
        $vipPackages = $products->map(function ($package) use ($user, $vipLevel) {
            $requiredVip = $this->calculateRequiredVip($package->price);
            $discountPercent = $this->calculateDiscount($requiredVip);
            $maxBuy = $this->calculateMaxBuy($requiredVip);

            $boughtCount = $user->transactions()
                ->where('product_id', $package->id)
                ->where('status', 'success')
                ->count();

            $finalPrice = $package->coinkey_amount * (1 - $discountPercent / 100);

            return [
                'product' => $package,
                'required_vip' => $requiredVip,
                'discount_percent' => $discountPercent,
                'final_price' => $finalPrice,
                'max_buy' => $maxBuy,
                'bought_count' => $boughtCount,
                'can_buy' => $boughtCount < $maxBuy,
                'is_locked' => $vipLevel < $requiredVip,
            ];
        });

        return view('wallet.buy-package', compact('vipPackages', 'wallet', 'vipLevel'));
    }

    /**
     * Xử lý mua gói Coinkey
     */
    public function purchasePackage(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Chuyển hướng đến trang thanh toán
        return redirect()->route('pay', $product->id);
    }

    /**
     * AJAX: Kiểm tra số dư
     */
    public function checkBalance()
    {
        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();

        return response()->json([
            'success' => true,
            'balance' => $wallet->balance,
            'formatted_balance' => number_format($wallet->balance, 2),
        ]);
    }

    /**
     * AJAX: Tính toán giá cho số phút tùy chỉnh
     */
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'minutes' => 'required|integer|min:1',
        ]);

        $minutes = $request->minutes;
        $coinkey = $this->coinkeyService->convertMinutesToCoinkey($minutes);
        $days = round($minutes / 1440, 2);

        return response()->json([
            'success' => true,
            'minutes' => $minutes,
            'coinkey_required' => $coinkey,
            'days' => $days,
            'formatted_coinkey' => number_format($coinkey, 2),
        ]);
    }

    // ==========================================
    // HELPER METHODS - VIP System Logic
    // ==========================================

    private function calculateRequiredVip(float $price): int
    {
        if ($price >= 500000) return 3;
        if ($price >= 200000) return 2;
        if ($price >= 100000) return 1;
        return 0;
    }

    private function calculateDiscount(int $requiredVip): int
    {
        return match ($requiredVip) {
            0 => 50,
            1 => 75,
            2 => 85,
            3 => 90,
            default => 0,
        };
    }

    private function calculateMaxBuy(int $requiredVip): int
    {
        return match ($requiredVip) {
            0 => 1,
            1 => 2,
            2 => 3,
            3 => 5,
            default => 1,
        };
    }
}