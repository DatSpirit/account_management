<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Api\KeyValidationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\KeyController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\WalletController;



// ==========================================
// WEBHOOK (No Auth/CSRF)
// ==========================================
Route::post('/payos/webhook', [WebhookController::class, 'handleWebhook'])
    ->name('api.payos.webhook');

// ==========================================
// ORDERS
// ==========================================
Route::post('/orders/create', [OrderController::class, 'createOrder'])
    ->name('api.orders.create');

// Public - Không cần token
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']); // nếu cần

// Protected - Cần token Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    // Route::put('/me/profile', [AuthController::class, 'updateProfile']);
    // Route::put('/me/password', [AuthController::class, 'updatePassword']);

    // Keys
    // Route::get('/keys', [KeyController::class, 'index']);
    // Route::get('/keys/{id}', [KeyController::class, 'show']);
    // Route::post('/keys/{id}/extend', [KeyController::class, 'extend']);
    // Route::get('/extension-packages', [KeyController::class, 'extensionPackages']);

    // // Transactions
    // Route::get('/transactions', [TransactionController::class, 'index']);
    // Route::get('/transactions/{id}', [TransactionController::class, 'show']);

    // // Wallet
    // Route::get('/wallet', [WalletController::class, 'show']);
    // Route::get('/wallet/balance', [WalletController::class, 'balance']);
});

// ==========================================
// HEALTH CHECK
// ==========================================
// Route::get('/health', function () {
//     return response()->json([
//         'status' => 'ok',
//         'service' => 'PayKMS API v2',
//         'version' => '2.0.0',
//         'timestamp' => now()->toIso8601String(),
//         'features' => [
//             'webhook' => true,
//             'key_validation' => true,
//             'enhanced_security' => true,
//         ]
//     ]);
// });


// ==========================================
// KEY VALIDATION API (Enhanced Security)
// ==========================================
// Route::prefix('validate-key')->name('api.validate-key.')->group(function () {
    
//     // Health check
//     Route::get('/health', [KeyValidationController::class, 'health'])
//         ->name('health');
    
//     // Single key validation
//     Route::post('/', [KeyValidationController::class, 'validate'])
//         ->middleware('throttle:60,1') // 60 requests per minute
//         ->name('validate');
    
//     // Batch validation (max 5 keys)
//     Route::post('/batch', [KeyValidationController::class, 'batchValidate'])
//         ->middleware('throttle:20,1') // 20 requests per minute
//         ->name('batch');
    
//     // Get key info (without validation logging)
//     Route::get('/info/{key_code}', [KeyValidationController::class, 'info'])
//         ->middleware('throttle:30,1') // 30 requests per minute
//         ->name('info');

//     // Rate limit status
//     Route::get('/rate-limit-status', [KeyValidationController::class, 'rateLimitStatus'])
//         ->middleware('throttle:10,1')
//         ->name('rate-limit-status');
    
//     // ==========================================
//     // ADMIN ENDPOINTS (Protected)
//     // ==========================================
//     Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        
//         // Clear all locks (emergency)
//         Route::post('/clear-locks', [KeyValidationController::class, 'clearLocks'])
//             ->name('clear-locks');
        
//         // Suspend suspicious key
//         Route::post('/suspend-key', [KeyValidationController::class, 'suspendKey'])
//             ->name('suspend-key');
//     });
// });

// ==========================================
// AUTHENTICATED API ROUTES
// ==========================================
// Route::middleware('auth:sanctum')->group(function () {
    
//     // User info
//     Route::get('/user', function (Request $request) {
//         return response()->json([
//             'success' => true,
//             'user' => $request->user()->only(['id', 'name', 'email', 'is_admin']),
//         ]);
//     });
    
//     // User's wallet info
//     Route::get('/wallet', function (Request $request) {
//         $wallet = $request->user()->getOrCreateWallet();
//         return response()->json([
//             'success' => true,
//             'wallet' => [
//                 'balance' => $wallet->balance,
//                 'total_deposited' => $wallet->total_deposited,
//                 'total_spent' => $wallet->total_spent,
//                 'vip_level' => $wallet->vip_level,
//                 'discount_percent' => $wallet->discount_percent,
//             ],
//         ]);
//     });
    
//     // User's keys
//     Route::get('/my-keys', function (Request $request) {
//         $keys = $request->user()->productKeys()
//             ->select(['id', 'key_code', 'status', 'expires_at', 'validation_count', 'created_at'])
//             ->orderBy('created_at', 'desc')
//             ->get()
//             ->map(function ($key) {
//                 return [
//                     'id' => $key->id,
//                     'key_code' => $key->key_code,
//                     'status' => $key->status,
//                     'expires_at' => $key->expires_at?->toIso8601String(),
//                     'remaining_minutes' => $key->getRemainingMinutes(),
//                     'validation_count' => $key->validation_count,
//                     'is_active' => $key->isActive(),
//                     'created_at' => $key->created_at->toIso8601String(),
//                 ];
//             });
            
//         return response()->json([
//             'success' => true,
//             'count' => $keys->count(),
//             'keys' => $keys,
//         ]);
//     });
// });