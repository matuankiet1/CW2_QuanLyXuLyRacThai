<?php

use Illuminate\Support\Facades\Route;
<<<<<<< Updated upstream
use App\Http\Controllers\AuthController; // Giả sử bạn có controller này
=======
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
>>>>>>> Stashed changes

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Đây là nơi bạn có thể đăng ký các route cho ứng dụng của mình.
|
*/

// Route mặc định, chuyển hướng đến trang đăng nhập nếu chưa đăng nhập,
// hoặc đến dashboard nếu đã đăng nhập.
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

<<<<<<< Updated upstream



// Route để hiển thị form đăng nhập
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
=======
// --- NHÓM ROUTE DÀNH CHO KHÁCH (CHƯA ĐĂNG NHẬP) ---
// Middleware 'guest' sẽ tự động chuyển hướng người dùng đã đăng nhập về trang chủ.
Route::middleware('guest')->group(function () {
    // Hiển thị form đăng nhập
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    // Xử lý đăng nhập
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
>>>>>>> Stashed changes

    // Hiển thị form đăng ký
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    // Xử lý đăng ký
    Route::post('register', [AuthController::class, 'register'])->name('register.post');

    // (Tùy chọn) Thêm các route cho quên mật khẩu nếu cần
    // Route::get('forgot-password', ...)->name('password.request');
});


<<<<<<< Updated upstream
// Route để xử lý dữ liệu từ form đăng ký
Route::post('register', [AuthController::class, 'register'])->name('register.post');
=======
// --- NHÓM ROUTE DÀNH CHO NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP ---
// Middleware 'auth' đảm bảo chỉ người dùng đã xác thực mới có thể truy cập.
Route::middleware('auth')->group(function () {
    // Trang Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý sự kiện (CRUD)
    Route::resource('events', EventController::class);

    // Xử lý đăng xuất
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/forgot-password', function () {
    return 'Chức năng quên mật khẩu đang được phát triển.';
})->name('password.request');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/dashboard', function () {
    return view('dashboard');
});
});
>>>>>>> Stashed changes
