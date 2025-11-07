# Notification Enhancements - Hướng dẫn sử dụng

## 📋 Tổng quan

Branch này đã thêm các chức năng nâng cao cho hệ thống thông báo:

1. ✅ Simple Notification System
2. ✅ Email Notifications  
3. ✅ Push Notifications (FCM)
4. ✅ Notification Preferences
5. ✅ Notification Templates

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
```

### 2. Email Notifications

```php
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;

Mail::to($user->email)->send(new NotificationMail('Tiêu đề', 'Nội dung'));
```

### 3. Push Notifications (FCM)

```php
use App\Notifications\FirebaseNotification;

$user->notify(new FirebaseNotification('Tiêu đề', 'Nội dung'));
```

### 4. Notification Preferences

```php
// Tạo hoặc cập nhật preferences
$user->preference()->updateOrCreate([], [
    'email' => true,
    'push' => false,
    'in_app' => true,
]);

// Kiểm tra preferences
if ($user->preference && $user->preference->email) {
    // Gửi email
}
```

### 5. Notification Templates

```php
use App\Services\TemplateNotificationService;

// Sử dụng template với biến
TemplateNotificationService::send(
    $userId,
    'event_reminder', // key của template
    [
        'username' => $user->name,
        'event' => 'Dọn rác chủ nhật'
    ]
);
```

Template cần được tạo trong database:

```php
NotificationTemplate::create([
    'key' => 'event_reminder',
    'title' => 'Nhắc nhở: {{{event}}}',
    'content' => 'Xin chào {{{username}}}, sự kiện {{{event}}} sắp diễn ra!'
]);
```

## 📝 Ví dụ tích hợp trong Controller

### Ví dụ: Gửi thông báo khi tạo bài viết mới

```php
use App\Services\NotificationService;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;

public function store(Request $request)
{
    // ... logic tạo bài viết ...
    
    // Gửi thông báo đơn giản
    NotificationService::send(
        $adminId,
        'Bài viết mới',
        'Một bài viết mới vừa được đăng.'
    );
    
    // Gửi email (nếu user có preference email = true)
    $admin = User::find($adminId);
    if ($admin->preference && $admin->preference->email) {
        Mail::to($admin->email)->send(
            new NotificationMail('Bài viết mới', 'Một bài viết mới vừa được đăng!')
        );
    }
    
    // Gửi push notification (nếu user có preference push = true)
    if ($admin->preference && $admin->preference->push) {
        $admin->notify(new FirebaseNotification('Bài viết mới', 'Một bài viết mới vừa được đăng!'));
    }
}
```

## 🗂️ Cấu trúc Files

```
app/
├── Models/
│   ├── SimpleNotification.php
│   ├── NotificationPreference.php
│   └── NotificationTemplate.php
├── Services/
│   ├── NotificationService.php
│   └── TemplateNotificationService.php
├── Mail/
│   └── NotificationMail.php
└── Notifications/
    └── FirebaseNotification.php

database/migrations/
├── 2025_11_07_035853_create_simple_notifications_table.php
├── 2025_11_07_040050_create_notification_preferences_table.php
└── 2025_11_07_040100_create_notification_templates_table.php

resources/views/emails/
└── notification.blade.php
```

## 🔄 Commits

1. `feat: add simple notification system (migration, model, service)`
2. `feat: add email notifications (Mail class and view)`
3. `feat: add push notifications (FCM) - config and notification class`
4. `feat: add notification preferences (migration, model, user relationship)`
5. `feat: add notification templates (migration, model, service)`

## ⚠️ Lưu ý

- FCM package chưa được cài đặt, cần chạy `composer require laravel-notification-channels/fcm` và uncomment code trong `FirebaseNotification.php`
- Cần chạy migrations trước khi sử dụng
- Cần cấu hình mail trong `.env` để gửi email
- Cần có Firebase project và server key để sử dụng FCM

## 🚀 Next Steps

- Tạo controller và routes cho Notification Preferences
- Tạo admin panel để quản lý Notification Templates
- Tích hợp vào các controller hiện có (PostController, EventController, etc.)
- Tạo command/job để xử lý scheduled notifications

