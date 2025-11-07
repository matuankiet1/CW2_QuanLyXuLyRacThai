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

    public function isLocal(): bool     { return $this->auth_provider === 'local'; }
    public function isGoogle(): bool    { return $this->auth_provider === 'google'; }
    public function isFacebook(): bool  { return $this->auth_provider === 'facebook'; }
}
