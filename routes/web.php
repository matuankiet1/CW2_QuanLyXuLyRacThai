<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // Giả sử bạn có controller này
use App\Http\Controllers\PostController; // Giả sử bạn có controller này

Route::get('/', function () {
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