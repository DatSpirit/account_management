<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;

// ===========================
// 🔹 TRANG CHỦ
// ===========================
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// ===========================
// 🔹 API THANH TOÁN (Public)
// ===========================
Route::post('/api/orders/create', [OrderController::class, 'createOrder'])
    ->name('api.orders.create');

Route::post('/api/payos/webhook', [PaymentController::class, 'handleWebhook'])
    ->name('api.payos.webhook');

// ===========================
// 🔹 SẢN PHẨM - NGƯỜI DÙNG
// ===========================
Route::middleware(['auth', 'verified'])->group(function () {

    // Danh sách sản phẩm (mọi user đều xem được)
    Route::get('/products', [ProductController::class, 'index'])->name('products');

    // Thanh toán sản phẩm
    Route::get('/pay/{id}', [ProductController::class, 'pay'])->name('pay');
    Route::get('/thankyou', [ProductController::class, 'thankyou'])->name('thankyou');
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

    // Dashboard của người dùng
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Hồ sơ cá nhân
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Trang hồ sơ riêng
    Route::get('/profile/user', [UserController::class, 'index'])->name('user.profile');

    // Gửi lại email xác minh
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

        // Dashboard chính của admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Quản lý người dùng
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
require __DIR__.'/auth.php';
