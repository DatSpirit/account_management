<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Danh sách giao dịch của user hiện tại
     */
    public function index(Request $request)
    {
        $transactions = $request->user()
            ->transactions()
            ->with('product') // Lấy thông tin sản phẩm liên quan
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách giao dịch thành công',
            'data' => $transactions
        ]);
    }

    /**
     * Chi tiết một giao dịch
     */
    public function show(Request $request, $id)
    {
        $transaction = $request->user()
            ->transactions()
            ->with(['product', 'user'])// Lấy thông tin sản phẩm và người dùng liên quan
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Lấy chi tiết giao dịch thành công',
            'data' => $transaction
        ]);
    }
}