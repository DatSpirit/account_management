<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletController extends Controller
{



    /**
     * Xem toàn bộ thông tin ví (số dư + lịch sử)
     */
    public function show(Request $request)
    {
        $wallet = $request->user()->getOrCreateWallet();

        $history = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->take(20) // 20 giao dịch gần nhất
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Thông tin ví Coinkey',
            'data' => [
                'balance' => $wallet->balance,
                'currency' => 'COINKEY',
                'recent_transactions' => $history
            ]
        ]);
    }
    /**
     * Xem số dư ví hiện tại
     */
    public function balance(Request $request)
    {
        $wallet = $request->user()->getOrCreateWallet();

        return response()->json([
            'success' => true,
            'message' => 'Số dư ví hiện tại',
            'data' => [
                'balance' => $wallet->balance, // Số dư ví
                'currency' => 'COINKEY'
            ]
        ]);
    }

    /**
     * Lịch sử giao dịch ví (nạp/rút)
     */
    public function history(Request $request)
    {
        $wallet = $request->user()->getOrCreateWallet();

        $transactions = $wallet->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Lịch sử giao dịch ví',
            'data' => $transactions
        ]);
    }
}
