<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Model: EventUser (Pivot Model)
 * 
 * Mô tả: Quản lý đăng ký tham gia sự kiện của sinh viên
 * 
 * Relationships:
 * - user: Sinh viên đăng ký
 * - event: Sự kiện được đăng ký
 */
class EventUser extends Pivot
{
    use HasFactory;

    /**
     * Tên bảng
     */
    protected $table = 'event_user';

    /**
     * Các trường có thể gán hàng loạt
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'registered_at',
        'confirmed_at',
        'attended_at',
    ];

    /**
     * Cast các trường dữ liệu
     */
    protected $casts = [
        'registered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'attended_at' => 'datetime',
    ];

    /**
     * Relationship: Sinh viên
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relationship: Sự kiện
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Scope: Lấy đăng ký theo trạng thái
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Lấy đăng ký đang chờ xác nhận
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Lấy đăng ký đã xác nhận
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope: Lấy đăng ký đã tham gia
     */
    public function scopeAttended($query)
    {
        return $query->where('status', 'attended');
    }

    /**
     * Xác nhận đăng ký (bởi admin)
     */
    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Điểm danh (bởi admin)
     */
    public function markAsAttended()
    {
        $this->update([
            'status' => 'attended',
            'attended_at' => now(),
        ]);
    }

    /**
     * Hủy đăng ký
     */
    public function cancel()
    {
        $this->update([
            'status' => 'canceled',
        ]);
    }
}
