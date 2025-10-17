<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AuthController; // Giả sử bạn có controller này
use App\Http\Controllers\PostController; // Giả sử bạn có controller này

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-mail', function () {
    try {
        Mail::raw('Đây là mail test gửi từ Laravel qua Gmail SMTP.', function ($message) {
            $message->to('21211tt4361@mail.tdc.edu.vn')
                ->subject('✅ Test gửi mail thành công!');
        });
        return 'Mail đã gửi thành công ✅';
    } catch (\Exception $e) {
        return 'Gửi mail thất bại ❌<br>' . $e->getMessage();
    }
});

//------------------------------------ AUTH -------------------------------------//

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);
// Route::get('forgot_password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot_password.show');

Route::middleware('guest')->group(function () {
    // Nhập email, gửi mã, và xác thực mã
    Route::get('/forgot_password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot_password.form');

    Route::post('/forgot_password/send_code', [AuthController::class, 'sendCode'])
        ->middleware('throttle:3,1') // chống spam gửi
        ->name('forgot_password.send');

    Route::post('/forgot_password/verify', [AuthController::class, 'verifyCode'])
        ->middleware('throttle:10,1') // chống spam nhập mã
        ->name('forgot_password.verify');

    // Đặt lại mật khẩu (chỉ vào được sau khi verify mã)
    Route::get('/reset_password', [AuthController::class, 'showResetPasswordForm'])->name('reset_password.form');
    Route::post('/reset_password', [AuthController::class, 'resetPassword'])->name('reset_password');
});

//--------------------------------- OTHER FUNCTIONS ---------------------------//

Route::resource('posts', PostController::class);