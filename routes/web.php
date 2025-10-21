<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CollectionScheduleController;

// Route mặc định, chuyển hướng đến trang đăng nhập nếu chưa đăng nhập,
// hoặc đến dashboard nếu đã đăng nhập.
// Route::get('/', function () {
//     if (Auth::check()) {
//         return redirect()->route('dashboard');
//     }
//     return redirect()->route('login');
// });

//------------------------------------ AUTH -------------------------------------//
// Login, register local
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

// Login, register bằng social (Google, Facebook)
Route::get('auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->name('login.social.redirect');
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('login.social.callback');

// Quên mật khẩu
Route::middleware('guest')->group(function () {
    // Nhập email, gửi mã, và xác thực mã
    Route::get('/forgot_password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot_password.form');

    Route::post('/forgot_password/send_code', [AuthController::class, 'sendCode'])
        ->middleware('throttle:3,1') // chống spam gửi
        ->name('forgot_password.send');

    Route::post('/forgot_password/verify', [AuthController::class, 'verifyCode'])
        ->middleware('throttle:10,1') // chống spam nhập mã
        ->name('forgot_password.verify');

    // Đặt lại mật khẩu
    Route::get('/reset_password', [AuthController::class, 'showResetPasswordForm'])->name('reset_password.form');
    Route::post('/reset_password', [AuthController::class, 'resetPassword'])->name('reset_password');
});

Route::get('/search-users', [AuthController::class, 'searchUsers'])->name('search.users');

//--------------------------------------- OTHER FUNCTIONS -------------------------------------//

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.admin');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// });

Route::get('/posts', [PostController::class, 'showAll'])->name('posts.home');
Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');



Route::get('/admin/posts', [PostController::class, 'index'])->name('admin.posts.index'); // Hiển thị danh sách bài viết
Route::get('/admin/posts/create', [PostController::class, 'create'])->name('posts.create'); // Tạo bài viết mới
Route::post('/admin/posts', [PostController::class, 'store'])->name('posts.store'); // Lưu bài viết mới
Route::get('/admin/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/admin/posts/{post}', [PostController::class, 'update'])->name('posts.update'); // Cập nhật bài viết
Route::delete('/admin/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// Collection Schedule Management
Route::resource('collection-schedules', CollectionScheduleController::class)->names([
    'index' => 'admin.collection-schedules.index',
    'store' => 'admin.collection-schedules.store',
    'edit' => 'admin.collection-schedules.edit',
    'update' => 'admin.collection-schedules.update',
    'destroy' => 'admin.collection-schedules.destroy',
]);


