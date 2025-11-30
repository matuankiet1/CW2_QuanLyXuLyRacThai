<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\TrashRequestStateMachine;

/**
 * Model TrashRequest - Yêu cầu thu gom rác
 * 
 * State machine: pending → assigned → staff_done → waiting_admin → admin_approved / admin_rejected
 */
class TrashRequest extends Model
{
    /**
     * Tên bảng
     */
    protected $table = 'trash_requests';

    /**
     * Khóa chính
     */
    protected $primaryKey = 'request_id';

    /**
     * Các trường có thể mass assign
     */
    protected $fillable = [
        'student_id',
        'location',
        'type',
        'description',
        'image',
        'status',
        'assigned_staff_id',
        'proof_image',
        'staff_notes',
        'admin_notes',
        'assigned_at',
        'staff_completed_at',
        'admin_reviewed_at',
    ];

    /**
     * Các trường sẽ được cast
     */
    protected $casts = [
        'assigned_at' => 'datetime',
        'staff_completed_at' => 'datetime',
        'admin_reviewed_at' => 'datetime',
    ];

    /**
     * Relationship: Student (người gửi yêu cầu)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id', 'user_id');
    }

    /**
     * Relationship: Staff được gán xử lý
     */
    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id', 'user_id');
    }

    /**
     * Kiểm tra có thể chuyển sang trạng thái mới không
     * 
     * @param string $newStatus
     * @return bool
     */
    public function canTransitionTo(string $newStatus): bool
    {
        return TrashRequestStateMachine::canTransition($this->status, $newStatus);
    }

    /**
     * Chuyển trạng thái (với validation)
     * 
     * @param string $newStatus
     * @param array $additionalData Dữ liệu bổ sung khi chuyển trạng thái
     * @return bool
     * @throws \Exception Nếu không thể chuyển trạng thái
     */
    public function transitionTo(string $newStatus, array $additionalData = []): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            throw new \Exception("Không thể chuyển từ trạng thái '{$this->status}' sang '{$newStatus}'");
        }

        // Cập nhật dữ liệu bổ sung trước
        foreach ($additionalData as $key => $value) {
            if (in_array($key, $this->fillable)) {
                $this->$key = $value;
            }
        }

        // Cập nhật timestamps dựa trên trạng thái
        switch ($newStatus) {
            case TrashRequestStateMachine::STATUS_ASSIGNED:
                $this->assigned_at = now();
                $this->status = $newStatus;
                break;
            case TrashRequestStateMachine::STATUS_STAFF_DONE:
                $this->staff_completed_at = now();
                $this->status = $newStatus;
                // Tự động chuyển sang waiting_admin sau khi staff_done
                // Kiểm tra xem có thể chuyển sang waiting_admin không
                if ($this->canTransitionTo(TrashRequestStateMachine::STATUS_WAITING_ADMIN)) {
                    $this->status = TrashRequestStateMachine::STATUS_WAITING_ADMIN;
                }
                break;
            case TrashRequestStateMachine::STATUS_ADMIN_APPROVED:
            case TrashRequestStateMachine::STATUS_ADMIN_REJECTED:
                $this->admin_reviewed_at = now();
                $this->status = $newStatus;
                break;
            default:
                $this->status = $newStatus;
                break;
        }

        return $this->save();
    }

    /**
     * Scope: Lấy các requests theo trạng thái
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Lấy các requests đang chờ admin duyệt
     */
    public function scopeWaitingAdmin($query)
    {
        return $query->where('status', TrashRequestStateMachine::STATUS_WAITING_ADMIN);
    }

    /**
     * Scope: Lấy các requests đã được gán cho staff
     */
    public function scopeAssigned($query)
    {
        return $query->where('status', TrashRequestStateMachine::STATUS_ASSIGNED);
    }

    /**
     * Scope: Lấy các requests của student
     */
    public function scopeForStudent($query, int $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope: Lấy các requests được gán cho staff
     */
    public function scopeForStaff($query, int $staffId)
    {
        return $query->where('assigned_staff_id', $staffId);
    }

    /**
     * Scope: Lấy các requests đang active (chưa được approve)
     */
    public function scopeActive($query)
    {
        return $query->where('status', '!=', TrashRequestStateMachine::STATUS_ADMIN_APPROVED);
    }

    /**
     * Kiểm tra request có thuộc về student không
     */
    public function belongsToStudent(int $studentId): bool
    {
        return $this->student_id === $studentId;
    }

    /**
     * Kiểm tra request có được gán cho staff không
     */
    public function isAssignedToStaff(int $staffId): bool
    {
        return $this->assigned_staff_id === $staffId;
    }

    /**
     * Kiểm tra request có đang chờ admin duyệt không
     */
    public function isWaitingAdmin(): bool
    {
        return $this->status === TrashRequestStateMachine::STATUS_WAITING_ADMIN;
    }

    /**
     * Kiểm tra request đã được approve chưa
     */
    public function isApproved(): bool
    {
        return $this->status === TrashRequestStateMachine::STATUS_ADMIN_APPROVED;
    }

    /**
     * Kiểm tra request có bị reject không
     */
    public function isRejected(): bool
    {
        return $this->status === TrashRequestStateMachine::STATUS_ADMIN_REJECTED;
    }
}
