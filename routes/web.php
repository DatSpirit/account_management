<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AdminDashboardController;

// Trang chủ
Route::get('/', function () {
    return view('welcome');
});

// ===========================
// 🔹 USER KHU VỰC NGƯỜI DÙNG
// ===========================
Route::middleware(['auth', 'verified'])
    ->group(function () {

        // Dashboard của User
        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');

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
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('admin.dashboard');

        // Trang quản lý người dùng
        Route::get('/users', [AdminController::class, 'index'])->name('admin.users');
        Route::get('/users/suggestions', [AdminController::class, 'suggestions'])->name('admin.users.suggestions');
        Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.edit');
        Route::put('/users/{user}', [AdminController::class, 'update'])->name('admin.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('admin.destroy');
        Route::get('/users/{user}/show', [AdminController::class, 'show'])->name('admin.users.show');
    });


// ===========================
// 🔹 XÁC THỰC / ĐĂNG NHẬP
// ===========================
require __DIR__.'/auth.php';
