<?php
use App\Http\Controllers\StaffHomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CollectionScheduleController;
use App\Http\Controllers\CollectionReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\WasteLogController;
use App\Http\Controllers\PostHomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SimpleNotificationController;
use App\Http\Controllers\NotificationPreferenceController;
use App\Http\Controllers\UserEventController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\UserStatisticsController;
use App\Http\Controllers\ChatbotController;
use App\Models\Banner;


// Route tạm thời để sửa CHECK constraint SQLite - PHẢI ĐẶT Ở ĐẦU FILE
Route::get('/fix-db-role', [AuthController::class, 'fixSqliteRoleConstraint']);

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

//------------------------------------ STAFF HOME -------------------------------------//
Route::prefix('staff')->name('staff.')->middleware(['auth', 'staff'])->group(function() {
    Route::get('/home', [StaffHomeController::class, 'index'])->name('home.index');
    Route::get('/home/contact', [StaffHomeController::class, 'contact'])->name('home.contact');
    Route::get('/home/about', [StaffHomeController::class, 'about'])->name('home.about');
    Route::get('/home/sortingGuide', [StaffHomeController::class, 'wasteSortingGuide'])->name('home.sorting_guide');
    Route::get('/events', [StaffHomeController::class, 'eventHome'])->name('events.index');
    Route::get('/events/{event}', [StaffHomeController::class, 'eventShow'])->name('events.show');
    Route::get('/posts', [StaffHomeController::class, 'postHome'])->name('posts.home');
    Route::get('/posts/{post}', [StaffHomeController::class, 'postShow'])->name('posts.show');
    Route::get('/collection_schedules', [StaffHomeController::class, 'collection_schedule'])->name('collection_schedules.index');
    Route::get('/waste-logs', [StaffHomeController::class, 'wasteLog'])->name('waste-logs.index');
    Route::get('/waste-logs/history', [StaffHomeController::class, 'history'])->name('waste-logs.history');
    Route::get('/statistics', [StaffHomeController::class, 'statistic'])->name('statistics.index');
    Route::get('/reports', [StaffHomeController::class, 'createReport'])->name('reports.create');
    //Route::get('/reports/waste', [StaffReportController::class, 'waste'])->name('reports.waste');
});

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

Route::post('/change-password', [AuthController::class, 'changePassword'])
    ->middleware('auth')
    ->name('change_password');

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
Route::get('/waste-logs', [WasteLogController::class, 'index'])->name('user.waste-logs.index');

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


        //Route::get('banners/{banner}/confirm-delete', 

        Route::get(
            'banners/{banner}/confirm-delete',

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

        Route::get('collection_reports', [WasteLogController::class, 'wasteReport'])->name('collection_reports.index');

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
        ->name('user.events.registerForm');

    // User Statistics
    Route::get('/statistics', [UserStatisticsController::class, 'index'])->name('user.statistics.index');

    Route::get('/collection_schedules', [HomeController::class, 'collection_schedules'])->name('user.collection_schedules.index');
});

//--------------------------------------- MANAGER ROUTES (Quản lý + Admin) -------------------------------------//
Route::middleware('manager')->group(function () {
    Route::get('/manager/dashboard', [DashboardController::class, 'manager'])->name('manager.dashboard');

    Route::get('collection-schedules/search', [CollectionScheduleController::class, 'search'])
        ->name('admin.collection-schedules.search');

    Route::delete('collection-schedules/delete-multiple', [CollectionScheduleController::class, 'destroyMultiple'])
        ->name('admin.collection-schedules.deleteMultiple');

    Route::get('/collection-schedules/export-excel', [CollectionScheduleController::class, 'exportExcel'])
        ->name('admin.collection-schedules.export-excel');

    Route::post('/collection-schedules/{id}/update-status', [CollectionScheduleController::class, 'updateStatus'])
        ->name('admin.collection-schedules.update-status');

    Route::get('/collection-schedules/get-waste-logs/{id}', [CollectionScheduleController::class, 'getWasteLogs'])
        ->name('admin.collection-schedules.get-waste-logs');

    Route::resource('collection-schedules', CollectionScheduleController::class)->names([
        'index' => 'admin.collection-schedules.index',
        'store' => 'admin.collection-schedules.store',
        'edit' => 'admin.collection-schedules.edit',
        'update' => 'admin.collection-schedules.update',
        'destroy' => 'admin.collection-schedules.destroy',
    ]);

    Route::prefix('admin/events')->name('admin.events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
        Route::get('/export', [EventController::class, 'export'])->name('export');

        Route::get('/{event}/rewards', [App\Http\Controllers\EventRewardController::class, 'index'])->name('rewards.index');
        Route::patch('/{event}/rewards/{user}', [App\Http\Controllers\EventRewardController::class, 'update'])->name('rewards.update');
        Route::post('/{event}/rewards/bulk-update', [App\Http\Controllers\EventRewardController::class, 'bulkUpdate'])->name('rewards.bulk-update');


        Route::get('/{event}/participants', [EventParticipantController::class, 'index'])
            ->name('participants'); // ✅ Thêm route index cho view quản lý

        Route::patch('/{event}/participants/{user}/confirm', [EventParticipantController::class, 'confirm'])
            ->name('participants.confirm');

        Route::patch('/{event}/participants/{user}/attend', [EventParticipantController::class, 'attend'])
            ->name('participants.attend');

        Route::get('/{event}/participants/pending', [EventParticipantController::class, 'pending'])
            ->name('participants.pending');

        Route::get('/{event}/pending', [EventParticipantController::class, 'index'])
            ->name('pending'); // ✅ Thêm route index cho view quản lý

        Route::post('/{event}/participants/bulk-confirm', [EventParticipantController::class, 'bulkConfirm'])
            ->name('participants.bulk-confirm');

        Route::post('/{event}/participants/bulk-attend', [EventParticipantController::class, 'bulkAttend'])
            ->name('participants.bulk-attend');

        Route::get('/{event}/participants/export', [EventParticipantController::class, 'export'])
            ->name('participants.export');

        Route::post('/events/{event}/register', [EventParticipantController::class, 'register'])
            ->name('user.events.register')
            ->middleware('auth'); // Chỉ cho user đã đăng nhập
    });

    Route::get('/manager/collection-reports', [CollectionReportController::class, 'managerIndex'])->name('manager.collection-reports.index');
    Route::post('/manager/collection-reports/{report}/approve', [CollectionReportController::class, 'approve'])->name('manager.collection-reports.approve');
});

Route::middleware('staff')->group(function () {
    Route::get('/staff/dashboard', [DashboardController::class, 'staff'])->name('staff.dashboard');

    Route::get('/staff/collection-reports', [CollectionReportController::class, 'staffIndex'])->name('staff.collection-reports.index');
    Route::get('/staff/collection-reports/{schedule}/create', [CollectionReportController::class, 'staffCreate'])->name('staff.collection-reports.create');
    Route::post('/staff/collection-reports/{schedule}', [CollectionReportController::class, 'staffStore'])->name('staff.collection-reports.store');
});

Route::middleware('student')->group(function () {
    Route::get('/student/dashboard', [DashboardController::class, 'student'])->name('student.dashboard');
});



// USER FEEDBACK ROUTES
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback', [FeedbackController::class, 'userIndex'])->name('feedback.index'); // Danh sách feedback của user
    Route::get('/feedback/{feedback}', [FeedbackController::class, 'userShow'])->name('feedback.show'); // Chi tiết feedback
});

// ADMIN FEEDBACK ROUTES
Route::middleware('admin')->prefix('admin/feedback')->name('admin.feedback.')->group(function () {
    Route::get('/', [FeedbackController::class, 'index'])->name('index');
    Route::get('/{feedback}', [FeedbackController::class, 'show'])->name('show');
    Route::post('/{feedback}/reply', [FeedbackController::class, 'reply'])->name('reply');
});


// --------------------------------------- USER PROFILE -------------------------------------//
Route::get('profile', [AuthController::class, 'getProfile'])->name('profile.show');
Route::post('update-profile', [AuthController::class, 'updateProfile'])->name('profile.update');
Route::post('update-avatar', [AuthController::class, 'updateAvatar'])->name('profile.update-avatar');
Route::delete('delete-avatar', [AuthController::class, 'deleteAvatar'])->name('profile.delete-avatar');

// Chatbot AI
Route::middleware('auth')->group(function () {
    Route::post('recycle-suggestion', [ChatbotController::class, 'suggestWasteRecycle'])->name('chatbot.recycle-suggestion');
});

// Route phục vụ ảnh banner - FIXED
Route::get('/banner-img/{filename}', function ($filename) {
    $path = storage_path('app/public/banners/' . $filename);
    
    if (!file_exists($path)) {
        // Log lỗi để debug
        \Log::error("Banner image not found: " . $path);
        abort(404);
    }
    
    return response()->file($path);
})->name('banner.image');
