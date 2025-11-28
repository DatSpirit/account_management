<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AllTransactionController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\MyTransactionController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AccountExpirationController;
// ===========================
// 🔹 TRANG CHỦ
// ===========================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Xử lý thanh toán - Payment Processing
Route::get('/payment/cancel-process', [OrderController::class, 'cancelPayment'])->name('payos.cancel-process');

// Trang thông báo hủy thanh toán - Cancel Payment Page
Route::get('/payment/cancel', function (Request $request) {
    return view('payment.cancel', [
        'orderCode' => $request->query('orderCode')
    ]);
})->name('pay.cancel-page');

// Trang cảm ơn sau khi thanh toán thành công - Thank You Page
Route::get('/thankyou', [OrderController::class, 'thankyou'])->name('thankyou');

// ===========================
// 🔹 SẢN PHẨM - NGƯỜI DÙNG
// ===========================
Route::middleware(['auth', 'verified'])->group(function () {

    // Danh sách sản phẩm - Product List
    Route::get('/products', [ProductController::class, 'index'])->name('products');

    // Thanh toán sản phẩm - Pay for Product
    Route::get('/pay/{id}', [OrderController::class, 'pay'])->name('pay');
});

// ===========================
// 🔹 SẢN PHẨM - QUẢN TRỊ (Admin Only)
// ===========================
Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    // Thêm sản phẩm mới 
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');

    // Sửa & Xóa sản phẩm 
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
});

// ===========================
// 🔹 USER KHU VỰC NGƯỜI DÙNG 
// ===========================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard của người dùng -- User Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // My Transactions 
    Route::get('/my-transactions', [MyTransactionController::class, 'index'])->name('transactions.index');
    // Transaction Detail - Chi tiết giao dịch
    Route::get('/my-transactions/{id}', [MyTransactionController::class, 'show'])->name('transactions.show');

    Route::post('/my-transactions/{id}/cancel', [MyTransactionController::class, 'cancel'])
        ->name('transactions.cancel');

    Route::post('/my-transactions/{id}/refund', [MyTransactionController::class, 'requestRefund'])
        ->name('transactions.refund');

    Route::get('/my-transactions/{id}/invoice', [MyTransactionController::class, 'downloadInvoice'])
        ->name('transactions.invoice');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    // Analytics Export 
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])
        ->name('analytics.export');

    // Export Actions- Excel, PDF, CSV
    Route::post('/analytics/export/excel', [AnalyticsController::class, 'exportExcel'])
        ->name('analytics.export.excel');
    Route::post('/analytics/export/pdf', [AnalyticsController::class, 'exportPdf'])
        ->name('analytics.export.pdf');
    Route::post('/analytics/export/csv', [AnalyticsController::class, 'exportCsv'])
        ->name('analytics.export.csv');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])
        ->name('settings.update');

    // Hồ sơ cá nhân - Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Trang hồ sơ riêng - User Profile Page
    Route::get('/profile/user', [UserController::class, 'index'])->name('user.profile');

    // Trung tâm Trợ giúp / FAQ - Help Center
    Route::get('/help-center', [SupportController::class, 'helpCenter'])->name('support.help_center');

    // Liên hệ Hỗ trợ / Contact Form - Contact Support
    Route::get('/contact', [SupportController::class, 'contactSupport'])->name('support.contact');
    Route::post('/contact', [SupportController::class, 'submitContact'])->name('support.contact.submit');

    // Gửi lại email xác minh -- Resend Verification Email
    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

// ===========================
// 🔹 ADMIN KHU VỰC QUẢN TRỊ
// ===========================
Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->group(function () {

        // Dashboard chính của admin -- Admin Main Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Quick extend từ dashboard -- Gia hạn nhanh từ dashboard
        Route::post('/quick-extend/{userId}', [AdminDashboardController::class, 'quickExtend'])
            ->name('admin.quick-extend');

        // Account Expiration Management-  Quản lý hết hạn tài khoản
        Route::prefix('account-expiration')->name('account-expiration.')->group(function () {

            Route::get('/{userId}/check', [AccountExpirationController::class, 'checkExpiration'])
                ->name('check');

            Route::post('/{userId}/extend', [AccountExpirationController::class, 'extendByDays'])
                ->name('extend');

            Route::post('/{userId}/set-date', [AccountExpirationController::class, 'setExpiryDate'])
                ->name('set-date');

            Route::post('/{userId}/remove-expiration', [AccountExpirationController::class, 'removeExpiration'])
                ->name('remove');

            Route::post('/{userId}/suspend', [AccountExpirationController::class, 'suspendAccount'])
                ->name('suspend');

            Route::post('/{userId}/activate', [AccountExpirationController::class, 'activateAccount'])
                ->name('activate');

            Route::get('/expiring-soon', [AccountExpirationController::class, 'getExpiringSoon'])
                ->name('expiring-soon');

            Route::get('/expired', [AccountExpirationController::class, 'getExpiredAccounts'])
                ->name('expired');
        });


        // Quản lý giao dịch -- All Transactions Management
        Route::get('/transactions', [AllTransactionController::class, 'index'])->name('admin.transactions.all-transactions');
        Route::get('/transactions/{id}', [AllTransactionController::class, 'show'])->name('admin.transactions.show');
        Route::patch('/transactions/{id}/status', [AllTransactionController::class, 'updateStatus'])->name('admin.transactions.update-status');
        Route::get('/transactions/export', [AllTransactionController::class, 'export'])->name('admin.transactions.export');

        // Quản lý người dùng -- User Management
        Route::get('/users', [AdminController::class, 'index'])->name('admin.users');
        Route::get('/users/suggestions', [AdminController::class, 'suggestions'])->name('admin.users.suggestions');
        Route::get('/users/{user}/show', [AdminController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [AdminController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
    });

// ===========================
// 🔹 XÁC THỰC / ĐĂNG NHẬP
// ===========================
require __DIR__ . '/auth.php';

// 🔹 CUSTOM CONFIRM PASSWORD (nếu cần giữ /confirm-password cũ)
// ===========================

Route::get('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'show'])
    ->name('password.confirm.custom');
Route::post('confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'store'])
    ->name('password.confirm.custom.store');
