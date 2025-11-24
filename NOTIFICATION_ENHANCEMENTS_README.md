# Notification Enhancements - Hướng dẫn sử dụng

## 📋 Tổng quan

Branch này đã thêm các chức năng nâng cao cho hệ thống thông báo:

1. ✅ Simple Notification System
2. ✅ Email Notifications
3. ✅ Push Notifications (FCM)
4. ✅ Notification Preferences
5. ✅ Notification Templates
6. ✅ Integrated Notification Service

## 🔧 Cài đặt

### 1. Chạy migrations

```bash
php artisan migrate
```

### 2. Cài đặt FCM Package (nếu muốn sử dụng Push Notifications)

```bash
composer require laravel-notification-channels/fcm
```

Sau khi cài đặt, uncomment phần code trong `app/Notifications/FirebaseNotification.php`:

```php
use NotificationChannels\Fcm\FcmMessage;

public function toFcm($notifiable)
{
    return FcmMessage::create()
        ->setNotification([
            'title' => $this->title,
            'body' => $this->body,
        ]);
}
```

### 3. Cấu hình .env

Thêm vào file `.env`:

```env
FCM_SERVER_KEY=your_firebase_server_key_here
```

## 📚 Sử dụng

### 1. Simple Notification Service

```php
use App\Services\NotificationService;

// Gửi thông báo đơn giản
NotificationService::send($userId, 'Tiêu đề', 'Nội dung thông báo');

// Gửi đến nhiều users
$userIds = [1, 2, 3];
NotificationService::sendToMany($userIds, 'Tiêu đề', 'Nội dung');

// Đánh dấu đã đọc
NotificationService::markAsRead($notificationId, $userId);

// Đánh dấu tất cả đã đọc
NotificationService::markAllAsRead($userId);
```

### 2. Email Notifications

```php
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;

Mail::to($user->email)->send(new NotificationMail('Tiêu đề', 'Nội dung', $user->name));
```

### 3. Push Notifications (FCM)

```php
use App\Notifications\FirebaseNotification;

// Gửi push notification
$user->notify(new FirebaseNotification('Tiêu đề', 'Nội dung'));

// Với data payload
$user->notify(new FirebaseNotification('Tiêu đề', 'Nội dung', ['key' => 'value']));
```

### 4. Notification Preferences

```php
// Tạo hoặc cập nhật preferences
$user->preference()->updateOrCreate([], [
    'email' => true,
    'push' => false,
    'in_app' => true,
]);

// Hoặc sử dụng helper method
NotificationPreference::updateOrCreateForUser($userId, [
    'email' => true,
    'push' => false,
    'in_app' => true,
]);

// Kiểm tra preferences
if ($user->preference && $user->preference->allowsEmail()) {
    // Gửi email
}

// Hoặc sử dụng helper methods trong User model
if ($user->allowsEmailNotifications()) {
    // Gửi email
}
```

### 5. Notification Templates

```php
use App\Services\TemplateNotificationService;
use App\Models\NotificationTemplate;

// Tạo template
NotificationTemplate::create([
    'key' => 'event_reminder',
    'title' => 'Nhắc nhở: {{{event}}}',
    'content' => 'Xin chào {{{username}}}, sự kiện {{{event}}} sắp diễn ra!'
]);

// Sử dụng template
TemplateNotificationService::send(
    $userId,
    'event_reminder',
    [
        'username' => $user->name,
        'event' => 'Dọn rác chủ nhật'
    ]
);

// Validate variables trước khi gửi
$missing = TemplateNotificationService::validateVariables('event_reminder', $variables);
if (!empty($missing)) {
    // Xử lý lỗi
}
```

### 6. Integrated Notification Service (Khuyến nghị sử dụng)

Service này tự động gửi thông báo qua tất cả các kênh (in-app + email + push) dựa trên preferences của user.

```php
use App\Services\IntegratedNotificationService;

// Gửi thông báo tích hợp
$result = IntegratedNotificationService::send(
    $userId,
    'Tiêu đề',
    'Nội dung thông báo'
);

// Kiểm tra kết quả
if ($result['success']) {
    $results = $result['data'];
    // $results['in_app'] - true/false
    // $results['email'] - true/false
    // $results['push'] - true/false
}

// Gửi đến nhiều users
$stats = IntegratedNotificationService::sendToMany(
    [1, 2, 3],
    'Tiêu đề',
    'Nội dung'
);

// Sử dụng template
IntegratedNotificationService::sendTemplate(
    $userId,
    'event_reminder',
    ['username' => $user->name, 'event' => 'Sự kiện']
);
```

## 📝 Ví dụ tích hợp trong Controller

### Ví dụ 1: Gửi thông báo khi tạo bài viết mới

```php
use App\Services\IntegratedNotificationService;

public function store(Request $request)
{
    // ... logic tạo bài viết ...

    // Gửi thông báo tích hợp đến admin
    $admins = User::where('role', 'admin')->pluck('user_id')->toArray();

    IntegratedNotificationService::sendToMany(
        $admins,
        'Bài viết mới',
        'Một bài viết mới vừa được đăng: ' . $post->title
    );

    return redirect()->route('admin.posts.index')
        ->with('success', 'Bài viết đã được tạo thành công!');
}
```

### Ví dụ 2: Sử dụng template cho thông báo sự kiện

```php
use App\Services\IntegratedNotificationService;

public function store(Request $request)
{
    // ... logic tạo sự kiện ...

    // Gửi thông báo đến tất cả sinh viên
    $userIds = User::where('role', 'student')->pluck('user_id')->toArray();

    foreach ($userIds as $userId) {
        $user = User::find($userId);
        IntegratedNotificationService::sendTemplate(
            $userId,
            'event_created',
            [
                'username' => $user->name,
                'event_title' => $event->title,
                'event_date' => $event->event_start_date->format('d/m/Y'),
                'event_location' => $event->location
            ]
        );
    }
}
```

### Ví dụ 3: Sử dụng Simple Notification với scopes

```php
use App\Models\SimpleNotification;

// Lấy thông báo chưa đọc của user
$unreadNotifications = SimpleNotification::forUser($userId)
    ->unread()
    ->latest()
    ->get();

// Đánh dấu đã đọc
$notification->markAsRead();

// Lấy số lượng thông báo chưa đọc
$unreadCount = $user->unread_notifications_count;
```

## 🗂️ Cấu trúc Files

```
app/
├── Models/
│   ├── SimpleNotification.php          # Model cho thông báo đơn giản
│   ├── NotificationPreference.php      # Model cho preferences
│   └── NotificationTemplate.php        # Model cho templates
├── Services/
│   ├── NotificationService.php         # Service gửi thông báo đơn giản
│   ├── TemplateNotificationService.php # Service sử dụng template
│   └── IntegratedNotificationService.php # Service tích hợp (khuyến nghị)
├── Mail/
│   └── NotificationMail.php            # Mail class cho email
└── Notifications/
    └── FirebaseNotification.php        # Notification class cho FCM

database/migrations/
├── 2025_11_07_035853_create_simple_notifications_table.php
├── 2025_11_07_040050_create_notification_preferences_table.php
└── 2025_11_07_040100_create_notification_templates_table.php

resources/views/emails/
└── notification.blade.php              # Email template
```

## 🎯 Features

### NotificationService

-   ✅ Gửi thông báo đơn giản
-   ✅ Gửi đến nhiều users
-   ✅ Validation và error handling
-   ✅ Logging
-   ✅ Đánh dấu đã đọc

### TemplateNotificationService

-   ✅ Sử dụng template với variables
-   ✅ Validate variables
-   ✅ Hỗ trợ {{key}} và {{{key}}}
-   ✅ Gửi đến nhiều users

### IntegratedNotificationService

-   ✅ Tự động gửi qua tất cả kênh
-   ✅ Tự động kiểm tra preferences
-   ✅ Fallback khi một kênh fail
-   ✅ Thống kê kết quả
-   ✅ Hỗ trợ template

### Models

-   ✅ Scopes (unread, read, forUser)
-   ✅ Helper methods
-   ✅ Relationships
-   ✅ Validation

## ⚠️ Lưu ý

-   **FCM Package**: Chưa được cài đặt mặc định, cần chạy `composer require laravel-notification-channels/fcm` và uncomment code trong `FirebaseNotification.php`
-   **Migrations**: Cần chạy migrations trước khi sử dụng
-   **Mail Configuration**: Cần cấu hình mail trong `.env` để gửi email
-   **Firebase**: Cần có Firebase project và server key để sử dụng FCM
-   **Preferences**: Mặc định tất cả notifications đều được bật (email, push, in_app = true)

## 🔄 Commits

1. `feat: add simple notification system (migration, model, service)`
2. `feat: add email notifications (Mail class and view)`
3. `feat: add push notifications (FCM) - config and notification class`
4. `feat: add notification preferences (migration, model, user relationship)`
5. `feat: add notification templates (migration, model, service)`
6. `docs: add README for notification enhancements`
7. `refactor: improve notification services and models with validation, error handling, and helper methods`
8. `feat: add integrated notification service and improve email template`
9. `refactor: improve FirebaseNotification with fallback and add helper methods to User model`

## 🚀 Best Practices

1. **Sử dụng IntegratedNotificationService**: Service này tự động xử lý tất cả các kênh và preferences
2. **Sử dụng Templates**: Tạo templates cho các thông báo thường dùng
3. **Validate Variables**: Luôn validate variables trước khi sử dụng template
4. **Error Handling**: Các services đã có error handling và logging, không cần try-catch trong controller
5. **Preferences**: Luôn tôn trọng preferences của user

## 📊 Performance

-   **Batch Operations**: Sử dụng `sendToMany()` cho nhiều users thay vì loop
-   **Scopes**: Sử dụng scopes để query hiệu quả hơn
-   **Lazy Loading**: Sử dụng `with()` để tránh N+1 query

---

_Last updated: {{ date('Y-m-d H:i:s') }}_
