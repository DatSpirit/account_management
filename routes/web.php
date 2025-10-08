<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController; // khai báo Controller cho User
use App\Http\Controllers\AdminController; // khai báo Controller cho Admin

Route::get('/', function () {
    return view('welcome');
});


// Trang Dashboard chính    
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Các route cần đăng nhập
Route::middleware('auth')->group(function () {


    // Hồ sơ người dùng
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // trang cá nhân User
    Route::get('/profile/user', [UserController::class, 'index'])->name('user.profile');

    // Trang quản lý người dùng (Admin Dashboard)
    Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

        // Danh sách người dùng
        Route::get('/users', [AdminController::class, 'index'])->name('admin.users');

        // API Gợi ý tìm kiếm (Autocomplete)
        Route::get('/users/suggestions', [AdminController::class, 'suggestions'])->name('admin.users.suggestions');

        // Chỉnh sửa người dùng
        Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('admin.edit');

        // Cập nhật người dùng
        Route::put('/users/{user}', [AdminController::class, 'update'])->name('admin.update');

        // Xóa người dùng
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('admin.destroy');

    });
});

require __DIR__.'/auth.php';
