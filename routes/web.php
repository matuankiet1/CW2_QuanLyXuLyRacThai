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
use App\Http\Controllers\WasteLogController;
use App\Http\Controllers\PostHomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SimpleNotificationController;
use App\Http\Controllers\NotificationPreferenceController;
use App\Http\Controllers\UserEventController;
use App\Http\Controllers\UserStatisticsController;

// Route để đánh dấu báo cáo đã đọc
Route::post('/reports/user-reports/{id}/mark-read', function ($id) {
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

// Login, register bằng social (Google, Facebook)
Route::get('auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->name('login.social.redirect');
Route::get('auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('login.social.callback');

Route::get('login/add-mail', [AuthController::class, 'showAddMailForm'])->name('login.add-mail');
Route::post('login/handleAddMailSubmit', [AuthController::class, 'handleAddMailSubmit'])->name('login.handle-add-mail-submit');

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

// Waste Logs
Route::get('/waste-logs/ai-suggest-waste-classifier', [WasteLogController::class, 'aiSuggestWasteClassifier'])
    ->middleware('throttle:30,1')->name('waste.ai-suggest'); // rate limit nhẹ
Route::get('/waste-logs/get-by-collection-schedules', [WasteLogController::class, 'getByCollectionSchedules'])
    ->name('waste-logs.get-by-collection-schedules');
Route::resource('waste-logs', WasteLogController::class);

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

        // 🟢 Banners
    Route::resource('banners', BannerController::class);

    Route::get('banners/{banner}/confirm-delete', 
        [BannerController::class, 'confirmDelete']
    )->name('banners.confirm-delete');

        // Role Management
        Route::get('roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
        Route::patch('roles/{user}', [App\Http\Controllers\RoleController::class, 'updateRole'])->name('roles.update');
        Route::post('roles/create', [App\Http\Controllers\RoleController::class, 'createAdmin'])->name('roles.create');
        Route::delete('roles/{user}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.destroy');
        
        // Permission Management
        Route::get('permissions', [App\Http\Controllers\PermissionController::class, 'index'])->name('permissions.index');
        Route::post('permissions', [App\Http\Controllers\PermissionController::class, 'store'])->name('permissions.store');
        Route::put('permissions/{permission}', [App\Http\Controllers\PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('permissions/{permission}', [App\Http\Controllers\PermissionController::class, 'destroy'])->name('permissions.destroy');
        Route::post('permissions/update-role-permissions', [App\Http\Controllers\PermissionController::class, 'updateRolePermissions'])->name('permissions.update-role-permissions');
    });

    // Collection Schedule
    Route::get('collection-schedules/search', [CollectionScheduleController::class, 'search'])
        ->name('admin.collection-schedules.search');

    Route::delete('collection-schedules/delete-multiple', [CollectionScheduleController::class, 'destroyMultiple'])
        ->name('admin.collection-schedules.deleteMultiple');

    Route::get('/collection-schedules/export-excel', [CollectionScheduleController::class, 'exportExcel'])
        ->name('admin.collection-schedules.export-excel');

    Route::post('/collection-schedules/{id}/update-status', [CollectionScheduleController::class, 'updateStatus'])
        ->name('admin.collection-schedules.update-status');

    Route::resource('collection-schedules', CollectionScheduleController::class)->names([
        'index' => 'admin.collection-schedules.index',
        'store' => 'admin.collection-schedules.store',
        'edit' => 'admin.collection-schedules.edit',
        'update' => 'admin.collection-schedules.update',
        'destroy' => 'admin.collection-schedules.destroy',
    ]);

    


    //Events
    // Events
    Route::prefix('admin/events')->name('admin.events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
        Route::get('/export', [EventController::class, 'export'])->name('export');
        
        // Quản lý điểm thưởng cho sinh viên tham gia sự kiện
        Route::get('/{event}/rewards', [App\Http\Controllers\EventRewardController::class, 'index'])->name('rewards.index');
        Route::patch('/{event}/rewards/{user}', [App\Http\Controllers\EventRewardController::class, 'update'])->name('rewards.update');
        Route::post('/{event}/rewards/bulk-update', [App\Http\Controllers\EventRewardController::class, 'bulkUpdate'])->name('rewards.bulk-update');
    });


    // Notifications (Admin)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('admin.notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('admin.notifications.store');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('admin.notifications.show');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('admin.notifications.destroy');
    Route::get('/notifications/{id}/download', [NotificationController::class, 'downloadAttachment'])->name('admin.notifications.download');

});

//--------------------------------------- USER NOTIFICATIONS (Sinh viên) -------------------------------------//
Route::middleware('auth')->group(function () {
    Route::get('/user-notifications', [NotificationController::class, 'userIndex'])->name('user.notifications.index');
    Route::get('/user-notifications/{id}', [NotificationController::class, 'userShow'])->name('user.notifications.show');
    Route::post('/user-notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('user.notifications.mark-all-read');

    // Simple Notifications
    Route::get('/simple-notifications', [SimpleNotificationController::class, 'index'])->name('user.simple-notifications.index');
    Route::get('/simple-notifications/{id}', [SimpleNotificationController::class, 'show'])->name('user.simple-notifications.show');
    Route::post('/simple-notifications/{id}/mark-read', [SimpleNotificationController::class, 'markAsRead'])->name('user.simple-notifications.mark-read');
    Route::post('/simple-notifications/mark-all-read', [SimpleNotificationController::class, 'markAllAsRead'])->name('user.simple-notifications.mark-all-read');

    // Notification Preferences
    Route::get('/notification-preferences', [NotificationPreferenceController::class, 'index'])->name('user.notification-preferences.index');
    Route::put('/notification-preferences', [NotificationPreferenceController::class, 'update'])->name('user.notification-preferences.update');

    // User Events
    Route::get('/events', [UserEventController::class, 'index'])->name('user.events.index');
    Route::get('/events/{id}', [UserEventController::class, 'show'])->name('user.events.show');
    Route::post('/events/{id}/register', [UserEventController::class, 'register'])->name('user.events.register');
    Route::post('/events/{id}/cancel', [UserEventController::class, 'cancel'])->name('user.events.cancel');
    Route::get('/events/{id}/register', [UserEventController::class, 'showRegisterForm'])
    ->name('users.events.registerForm');

    // User Statistics
    Route::get('/statistics', [UserStatisticsController::class, 'index'])->name('user.statistics.index');
});
