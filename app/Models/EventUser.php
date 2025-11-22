<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventUser extends Pivot
{
    protected $table = 'event_user';

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'registered_at',
        'confirmed_at',
        'attended_at',
        'reward_points',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'attended_at' => 'datetime',
    ];

    // --- Constants ---
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ATTENDED = 'attended';
    const STATUS_CANCELED = 'canceled';

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->registered_at = now();
            $model->status = $model->status ?? self::STATUS_PENDING;
        });
    }

    // --- Relationships ---
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id'); // CHỈNH SỬA
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    // --- Scopes ---
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopeAttended($query)
    {
        return $query->where('status', self::STATUS_ATTENDED);
    }

    // --- Actions ---
    public function confirm()
    {
        $this->update([
            'status' => self::STATUS_CONFIRMED,
            'confirmed_at' => now(),
        ]);
    }

    public function markAsAttended()
    {
        $this->update([
            'status' => self::STATUS_ATTENDED,
            'attended_at' => now(),
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => self::STATUS_CANCELED,
        ]);
    }
}
