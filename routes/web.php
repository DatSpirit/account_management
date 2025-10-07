<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController; // khai báo Controller cho User
use App\Http\Controllers\AdminController; // khai báo Controller cho Admin

Route::get('/', function () {
    return view('welcome');
});


// Trang quản lý người dùng (Admin Dashboard)
Route::get('/admin/users', [AdminController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.users');

// Trang Dashboard chính    
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Các route cần đăng nhập
Route::middleware('auth')->group(function () {
    // Trang chỉnh sửa hồ sơ
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // trang cá nhân User
    Route::get('/profile/user', [UserController::class, 'index'])->name('user.profile');

});

require __DIR__.'/auth.php';
