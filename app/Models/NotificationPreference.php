<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'email', 'push', 'in_app'];

    protected $casts = [
        'email' => 'boolean',
        'push' => 'boolean',
        'in_app' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship với User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Tạo hoặc cập nhật preferences với giá trị mặc định
     *
     * @param int $userId
     * @param array $preferences
     * @return static
     */
    public static function updateOrCreateForUser($userId, array $preferences = [])
    {
        $defaults = [
            'email' => true,
            'push' => true,
            'in_app' => true,
        ];

        $data = array_merge($defaults, $preferences);
        $data['user_id'] = $userId;

        return self::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }

    /**
     * Kiểm tra user có bật email notifications không
     */
    public function allowsEmail(): bool
    {
        return $this->email ?? true;
    }

    /**
     * Kiểm tra user có bật push notifications không
     */
    public function allowsPush(): bool
    {
        return $this->push ?? true;
    }

    /**
     * Kiểm tra user có bật in-app notifications không
     */
    public function allowsInApp(): bool
    {
        return $this->in_app ?? true;
    }
}
