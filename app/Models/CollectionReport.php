<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'staff_id',
        'total_weight',
        'organic_weight',
        'recyclable_weight',
        'hazardous_weight',
        'other_weight',
        'photo_path',
        'notes',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(CollectionSchedule::class, 'schedule_id', 'schedule_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id', 'user_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}

