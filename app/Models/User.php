<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Chỉ định tên khóa chính của bảng.
     *
     * @var string
     */
    protected $primaryKey = 'user_id'; // <-- Dòng quan trọng nhất để sửa lỗi

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'auth_provider',
        'provider_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relationship với Post model
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'user_id');
    }

    /**
     * Relationship với Notification (thông báo đã gửi)
     */
    public function sentNotifications()
    {
        return $this->hasMany(Notification::class, 'sender_id', 'user_id');
    }

    /**
     * Relationship với Notification (thông báo đã nhận)
     */
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_user', 'user_id', 'notification_id')
                    ->withPivot('read_at')
                    ->withTimestamps();
    }

    /**
     * Relationship với NotificationPreference
     */
    public function preference()
    {
        return $this->hasOne(NotificationPreference::class, 'user_id', 'user_id');
    }

    /**
     * Relationship với SimpleNotification
     */
    public function simpleNotifications()
    {
        return $this->hasMany(SimpleNotification::class, 'user_id', 'user_id');
    }

    /**
     * Lấy số lượng thông báo chưa đọc
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->simpleNotifications()->unread()->count();
    }

    /**
     * Kiểm tra user có bật email notifications không
     */
    public function allowsEmailNotifications(): bool
    {
        $preference = $this->preference;
        return $preference ? $preference->allowsEmail() : true;
    }

    /**
     * Kiểm tra user có bật push notifications không
     */
    public function allowsPushNotifications(): bool
    {
        $preference = $this->preference;
        return $preference ? $preference->allowsPush() : true;
    }

    /**
     * Kiểm tra user có bật in-app notifications không
     */
    public function allowsInAppNotifications(): bool
    {
        $preference = $this->preference;
        return $preference ? $preference->allowsInApp() : true;
    }

    public function isLocal(): bool     { return $this->auth_provider === 'local'; }
    public function isGoogle(): bool    { return $this->auth_provider === 'google'; }
    public function isFacebook(): bool  { return $this->auth_provider === 'facebook'; }

    /**
     * Relationship: Danh sách sự kiện đã tạo (Admin)
     */
    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'created_by', 'user_id');
    }

    /**
     * Relationship: Danh sách sự kiện đã đăng ký tham gia (Sinh viên)
     */
    public function registeredEvents()
    {
        return $this->belongsToMany(Event::class, 'event_user', 'user_id', 'event_id')
                    ->withPivot('status', 'registered_at', 'confirmed_at', 'attended_at')
                    ->withTimestamps()
                    ->using(EventUser::class);
    }

    /**
     * Relationship: Danh sách đăng ký sự kiện (EventUser)
     */
    public function eventRegistrations()
    {
        return $this->hasMany(EventUser::class, 'user_id', 'user_id');
    }
}
