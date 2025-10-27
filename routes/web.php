<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CollectionScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

//------------------------------------ TRANG CHỦ -------------------------------------//
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

//------------------------------------ AUTH -------------------------------------//
// Login, register local
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

// Login, register bằng social (Google, Facebook)
Route::get('auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->name('login.social.redirect');
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('login.social.callback');

//------------------------------------ QUÊN MẬT KHẨU -------------------------------------//
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

//--------------------------------------- POST ROUTES (Mọi người đều truy cập được) -------------------------------------//
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
});

//--------------------------------------- ADMIN ROUTES (Chỉ admin mới truy cập được) -------------------------------------//
Route::middleware('admin')->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.admin');
    
    // Search users
    Route::get('/search-users', [AuthController::class, 'searchUsers'])->name('search.users');
    
    // CRUD Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('posts', PostController::class);
        Route::resource('users', UserController::class);
    });
    
    // Collection Schedule
    Route::get('collection-schedules/search', [CollectionScheduleController::class, 'search'])
        ->name('admin.collection-schedules.search');
        
    Route::resource('collection-schedules', CollectionScheduleController::class)->names([
        'index' => 'admin.collection-schedules.index',
        'store' => 'admin.collection-schedules.store',
        'edit' => 'admin.collection-schedules.edit',
        'update' => 'admin.collection-schedules.update',
        'destroy' => 'admin.collection-schedules.destroy',
    ]);
    
    // Banners
    Route::resource('banners', BannerController::class);
});
