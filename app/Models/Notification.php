<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'sender_id',
        'title',
        'content',
        'type',
        'attachment',
        'send_to_type',
        'target_role',
        'status',
        'scheduled_at',
        'sent_at',
        'total_recipients',
        'read_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship với User (người gửi)
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }

    /**
     * Relationship với nhiều users (người nhận)
     */
    public function recipients(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id')
                    ->withPivot('read_at')
                    ->withTimestamps();
    }

    /**
     * Kiểm tra thông báo đã được gửi chưa
     */
    public function isSent(): bool
    {
        return $this->status === 'sent' && !is_null($this->sent_at);
    }

    /**
     * Kiểm tra thông báo có lên lịch chưa
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled' && !is_null($this->scheduled_at);
    }

    /**
     * Lấy phần trăm người đã đọc
     */
    public function getReadPercentage(): float
    {
        if ($this->total_recipients === 0) {
            return 0;
        }
        return round(($this->read_count / $this->total_recipients) * 100, 2);
    }
}
