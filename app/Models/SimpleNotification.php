<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SimpleNotification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'message', 'is_read'];

    protected $casts = [
        'is_read' => 'boolean',
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
     * Scope: Lọc thông báo chưa đọc
     */
    public function scopeUnread(Builder $query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: Lọc thông báo đã đọc
     */
    public function scopeRead(Builder $query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope: Lọc theo user
     */
    public function scopeForUser(Builder $query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Đánh dấu thông báo đã đọc
     */
    public function markAsRead(): bool
    {
        if ($this->is_read) {
            return false;
        }

        return $this->update(['is_read' => true]);
    }

    /**
     * Đánh dấu thông báo chưa đọc
     */
    public function markAsUnread(): bool
    {
        if (!$this->is_read) {
            return false;
        }

        return $this->update(['is_read' => false]);
    }

    /**
     * Kiểm tra thông báo đã đọc chưa
     */
    public function isUnread(): bool
    {
        return !$this->is_read;
    }
}
