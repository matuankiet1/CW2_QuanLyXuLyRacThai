<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReport extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'status',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship với User model
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Kiểm tra báo cáo đã được đọc chưa
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Đánh dấu báo cáo là đã đọc
     */
    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }
}
