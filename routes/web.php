<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CollectionScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PostHomeController;

// Route để đánh dấu báo cáo đã đọc
Route::post('/reports/user-reports/{id}/mark-read', function($id) {
    $report = App\Models\UserReport::findOrFail($id);
    $report->markAsRead();
    
    return response()->json(['success' => true]);
});

//------------------------------------ TRANG CHỦ -------------------------------------//
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');

//------------------------------------ ADMIN HOME -------------------------------------//
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/home', [App\Http\Controllers\AdminHomeController::class, 'index'])->name('home');
    Route::get('/home/about', [App\Http\Controllers\AdminHomeController::class, 'about'])->name('home.about');
    Route::get('/home/contact', [App\Http\Controllers\AdminHomeController::class, 'contact'])->name('home.contact');
});

//------------------------------------ AUTH -------------------------------------//
// Login, register local
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout'); // Lê Tâm: Đã có hàm Logout trong Controller, nên chỉ cần Route::post('logout', [AuthController::class, 'logout'])->name('logout'); 
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

// Route::get('logout', [AuthController::class, 'logout'])->name('logout');

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

//--------------------------------------- POST ROUTES (Mọi người đều truy cập được) -------------------------------------//
Route::get('/posts', [PostHomeController::class, 'index'])->name('user.posts.home');
Route::get('/posts/{id}', [PostHomeController::class, 'show'])->name('user.posts.show');

//--------------------------------------- USER REPORTS -------------------------------------//
Route::middleware('auth')->group(function () {
    Route::get('/reports/create', [App\Http\Controllers\UserReportController::class, 'create'])->name('user.reports.create');
    Route::post('/reports', [App\Http\Controllers\UserReportController::class, 'store'])->name('user.reports.store');
});

//--------------------------------------- ADMIN ROUTES (Chỉ admin mới truy cập được) -------------------------------------//
Route::middleware('admin')->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.admin');

    // Search users
    Route::get('/search-users', [AuthController::class, 'searchUsers'])->name('search.users');

    // Reports
    Route::prefix('reports')->name('admin.reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('/users', [App\Http\Controllers\ReportController::class, 'users'])->name('users');
        Route::get('/posts', [App\Http\Controllers\ReportController::class, 'posts'])->name('posts');
        Route::get('/schedules', [App\Http\Controllers\ReportController::class, 'schedules'])->name('schedules');
        Route::get('/export', [App\Http\Controllers\ReportController::class, 'export'])->name('export');
        
        // User Reports
        Route::get('/user-reports', [App\Http\Controllers\UserReportController::class, 'index'])->name('user-reports');
        Route::get('/user-reports/{id}', [App\Http\Controllers\UserReportController::class, 'show'])->name('user-reports.show');
        Route::post('/user-reports/{id}/status', [App\Http\Controllers\UserReportController::class, 'updateStatus'])->name('user-reports.update-status');
        Route::post('/user-reports/{id}/mark-read', [App\Http\Controllers\UserReportController::class, 'markAsRead'])->name('user-reports.mark-read');
        Route::post('/user-reports/{id}/mark-unread', [App\Http\Controllers\UserReportController::class, 'markAsUnread'])->name('user-reports.mark-unread');
        Route::post('/user-reports/{id}/status-ajax', [App\Http\Controllers\UserReportController::class, 'updateStatusAjax'])->name('user-reports.update-status-ajax');
    });

    // CRUD Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('posts', PostController::class);
        Route::resource('users', UserController::class);

        // Role Management
        Route::get('roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
        Route::patch('roles/{user}', [App\Http\Controllers\RoleController::class, 'updateRole'])->name('roles.update');
        Route::post('roles/create', [App\Http\Controllers\RoleController::class, 'createAdmin'])->name('roles.create');
        Route::delete('roles/{user}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.destroy');
    });

    // Collection Schedule
    Route::get('collection-schedules/search', [CollectionScheduleController::class, 'search'])
        ->name('admin.collection-schedules.search');

    Route::delete('collection-schedules/delete-multiple', [CollectionScheduleController::class, 'destroyMultiple'])
        ->name('admin.collection-schedules.deleteMultiple');

    Route::resource('collection-schedules', CollectionScheduleController::class)->names([
        'index' => 'admin.collection-schedules.index',
        'store' => 'admin.collection-schedules.store',
        'edit' => 'admin.collection-schedules.edit',
        'update' => 'admin.collection-schedules.update',
        'destroy' => 'admin.collection-schedules.destroy',
    ]);

    // 🟢 Banners
Route::prefix('banners')->name('admin.banners.')->group(function () {
    Route::get('/{banner}/confirm-delete', [BannerController::class, 'confirmDelete'])
        ->name('confirm-delete');
    Route::resource('/', BannerController::class)->parameters(['' => 'banner']);
});


    //Events
    // Events
    Route::prefix('events')->name('admin.events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
        Route::get('/export', [EventController::class, 'export'])->name('export');
    });

});
