<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Carbon\Carbon;

class NotificationUser extends Pivot
{
    protected $table = 'notification_user';

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Kiểm tra thông báo đã được đọc chưa
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Đánh dấu thông báo đã đọc
     */
    public function markAsRead(): void
    {
        if (!$this->isRead()) {
            $this->read_at = Carbon::now();
            $this->save();
        }
    }
}
