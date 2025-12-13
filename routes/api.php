<?php

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\OrderController;

use App\Http\Controllers\Api\KeyValidationController;


// ✅ Webhook PayOS (không có middleware auth, không có CSRF)
Route::post('/payos/webhook', [WebhookController::class, 'handleWebhook'])
    ->name('api.payos.webhook');

// ✅ API tạo order
Route::post('/orders/create', [OrderController::class, 'createOrder'])
    ->name('api.orders.create');


// ✅ API xác thực key sản phẩm

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'PayKMS API',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// ===========================
// KEY VALIDATION API (Public - No Auth Required)
// ===========================
Route::prefix('validate-key')->group(function () {
    // Health check for validation service
    Route::get('/health', [KeyValidationController::class, 'health']);
    
    // Single key validation
    Route::post('/', [KeyValidationController::class, 'validate']);
    
    // Batch validation
    Route::post('/batch', [KeyValidationController::class, 'batchValidate']);
    
    // Get key info (without validation logging)
    Route::get('/info/{key_code}', [KeyValidationController::class, 'info']);
});

// ===========================
// AUTHENTICATED API ROUTES (Optional - for future expansion)
// ===========================
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // User's wallet info
    Route::get('/wallet', function (Request $request) {
        $wallet = $request->user()->getOrCreateWallet();
        return response()->json([
            'balance' => $wallet->balance,
            'total_deposited' => $wallet->total_deposited,
            'total_spent' => $wallet->total_spent,
        ]);
    });
    
    // User's keys
    Route::get('/my-keys', function (Request $request) {
        $keys = $request->user()->productKeys()
            ->select(['id', 'key_code', 'status', 'expires_at', 'validation_count'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'keys' => $keys,
        ]);
    });
});