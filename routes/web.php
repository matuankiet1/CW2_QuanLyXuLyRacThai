<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Giả sử bạn có controller này
use App\Http\Controllers\PostController; // Giả sử bạn có controller này
use App\Http\Controllers\BannerController;

Route::get('dashboard', function () {
    return view('welcome');
});




// Route để hiển thị form đăng nhập
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

// Route để xử lý dữ liệu từ form đăng nhập
Route::post('login', [AuthController::class, 'login'])->name('login.post');

// Route cho các chức năng khác
Route::get('register', function() { /* ... */ })->name('register');
Route::get('forgot-password', function() { /* ... */ })->name('password.request');

Route::resource('posts', PostController::class);

// Route cho chức năng crud banner
Route::resource('banners', BannerController::class);
Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
Route::get('/banners/create', [BannerController::class, 'create'])->name('banners.create');
Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
Route::get('/banners/{id}/edit', [BannerController::class, 'edit'])->name('banners.edit');
Route::put('/banners/{id}', [BannerController::class, 'update'])->name('banners.update');
Route::delete('/banners/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');