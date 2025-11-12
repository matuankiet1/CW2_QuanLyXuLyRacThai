<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

/**
 * Model: Event
 * 
 * Mô tả: Quản lý sự kiện thu gom rác
 * 
 * Relationships:
 * - createdBy: Người tạo sự kiện (User - admin)
 * - participants: Danh sách sinh viên tham gia (User - thông qua EventUser)
 * - registrations: Danh sách đăng ký (EventUser)
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'register_date',
        'register_end_date',
        'event_start_date',
        'event_end_date',
        'location',
        'participants',
        'status',
        'description',
        'capacity', // Số lượng tối đa
        'created_by', // Người tạo
    ];

    /**
     * Cast các trường dữ liệu
     */
    protected $casts = [
        'register_date' => 'datetime',
        'register_end_date' => 'datetime',
        'event_start_date' => 'datetime',
        'event_end_date' => 'datetime',
    ];

    /**
     * Relationship: Người tạo sự kiện (Admin)
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    /**
     * Relationship: Danh sách sinh viên tham gia (thông qua EventUser)
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id')
                    ->withPivot('status', 'registered_at', 'confirmed_at', 'attended_at')
                    ->withTimestamps()
                    ->using(EventUser::class);
    }

    /**
     * Relationship: Danh sách đăng ký (EventUser)
     */
    public function registrations()
    {
        return $this->hasMany(EventUser::class, 'event_id');
    }

    /**
     * Scope: Lấy sự kiện sắp tới
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_start_date', '>', now())
                     ->where('status', 'upcoming');
    }

    /**
     * Scope: Lấy sự kiện đang diễn ra
     */
    public function scopeOngoing($query)
    {
        return $query->where('event_start_date', '<=', now())
                     ->where('event_end_date', '>=', now())
                     ->where('status', '!=', 'completed');
    }

    /**
     * Scope: Lấy sự kiện đã kết thúc
     */
    public function scopeEnded($query)
    {
        return $query->where('event_end_date', '<', now())
                     ->orWhere('status', 'completed');
    }

    /**
     * Kiểm tra sự kiện có còn chỗ không
     */
    public function hasAvailableSlots(): bool
    {
        if (!$this->capacity) {
            return true; // Không giới hạn
        }
        
        $registeredCount = $this->registrations()
            ->whereIn('status', ['pending', 'confirmed', 'attended'])
            ->count();
        
        return $registeredCount < $this->capacity;
    }

    /**
     * Lấy số lượng chỗ còn lại
     */
    public function getAvailableSlotsAttribute(): int
    {
        if (!$this->capacity) {
            return 999999; // Không giới hạn
        }
        
        $registeredCount = $this->registrations()
            ->whereIn('status', ['pending', 'confirmed', 'attended'])
            ->count();
        
        return max(0, $this->capacity - $registeredCount);
    }

    /**
     * Kiểm tra sinh viên đã đăng ký chưa
     */
    public function isRegisteredBy($userId): bool
    {
        return $this->registrations()
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed', 'attended'])
            ->exists();
    }

    /**
     * Kiểm tra có thể đăng ký không (thời gian đăng ký còn hiệu lực)
     */
    public function canRegister(): bool
    {
        $now = now();
        return $now >= $this->register_date && 
               $now <= $this->register_end_date &&
               $this->status === 'upcoming' &&
               $this->hasAvailableSlots();
    }

    /**
     * Lấy số lượng đăng ký theo trạng thái
     */
    public function getRegistrationCountByStatus($status): int
    {
        return $this->registrations()->where('status', $status)->count();
    }
}
