<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController; // khai báo Controller cho User
use App\Http\Controllers\AdminController; // khai báo Controller cho Admin

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// trang cá nhân User
    Route::get('/profile/user', [UserController::class, 'index'])->name('user.profile');
// trang quản lý người dùng
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
});

require __DIR__.'/auth.php';
