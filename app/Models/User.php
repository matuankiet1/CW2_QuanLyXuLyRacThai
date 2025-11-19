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

    /**
     * ========== ROLE & PERMISSION METHODS ==========
     */

    /**
     * Kiểm tra user có phải admin không
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Kiểm tra user có phải manager không
     */
    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    /**
     * Kiểm tra user có phải staff không
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Kiểm tra user có phải student không
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Kiểm tra user có role trong danh sách không
     */
    public function hasRole(array|string $roles): bool
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }
        return in_array($this->role, $roles);
    }

    /**
     * Kiểm tra user có permission không
     */
    public function hasPermission(string $permissionName): bool
    {
        // Admin có tất cả quyền
        if ($this->isAdmin()) {
            return true;
        }

        // Lấy permission từ database
        $permission = \App\Models\Permission::where('name', $permissionName)->first();
        if (!$permission) {
            return false;
        }

        // Kiểm tra role có permission này không
        return \App\Models\RolePermission::where('role', $this->role)
            ->where('permission_id', $permission->id)
            ->exists();
    }

    /**
     * Lấy tất cả permissions của user
     */
    public function getPermissions(): array
    {
        if ($this->isAdmin()) {
            // Admin có tất cả permissions
            return \App\Models\Permission::pluck('name')->toArray();
        }

        return \App\Models\RolePermission::getPermissionsForRole($this->role);
    }

    /**
     * Kiểm tra user có bất kỳ permission nào trong danh sách không
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Kiểm tra user có tất cả permissions trong danh sách không
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }
}
