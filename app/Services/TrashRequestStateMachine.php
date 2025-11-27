<?php

namespace App\Services;

/**
 * State Machine Validator cho TrashRequest
 * 
 * Quản lý luồng chuyển trạng thái hợp lệ:
 * pending → assigned → staff_done → waiting_admin → admin_approved / admin_rejected
 * 
 * Khi bị reject, staff có thể cập nhật lại từ admin_rejected → staff_done
 */
class TrashRequestStateMachine
{
    /**
     * Định nghĩa các trạng thái hợp lệ
     */
    const STATUS_PENDING = 'pending';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_STAFF_DONE = 'staff_done';
    const STATUS_WAITING_ADMIN = 'waiting_admin';
    const STATUS_ADMIN_APPROVED = 'admin_approved';
    const STATUS_ADMIN_REJECTED = 'admin_rejected';

    /**
     * Định nghĩa các chuyển trạng thái hợp lệ
     * Key: trạng thái hiện tại
     * Value: danh sách trạng thái có thể chuyển đến
     */
    private static array $allowedTransitions = [
        self::STATUS_PENDING => [
            self::STATUS_ASSIGNED, // Hệ thống tự động gán
        ],
        self::STATUS_ASSIGNED => [
            self::STATUS_STAFF_DONE, // Staff hoàn thành công việc
        ],
        self::STATUS_STAFF_DONE => [
            self::STATUS_WAITING_ADMIN, // Tự động chuyển sau khi staff cập nhật
        ],
        self::STATUS_WAITING_ADMIN => [
            self::STATUS_ADMIN_APPROVED, // Admin duyệt
            self::STATUS_ADMIN_REJECTED, // Admin từ chối
        ],
        self::STATUS_ADMIN_REJECTED => [
            self::STATUS_STAFF_DONE, // Staff cập nhật lại sau khi bị reject
        ],
        // admin_approved là trạng thái cuối, không thể chuyển sang trạng thái khác
        self::STATUS_ADMIN_APPROVED => [],
    ];

    /**
     * Kiểm tra xem có thể chuyển từ trạng thái này sang trạng thái khác không
     * 
     * @param string $currentStatus Trạng thái hiện tại
     * @param string $newStatus Trạng thái muốn chuyển đến
     * @return bool
     */
    public static function canTransition(string $currentStatus, string $newStatus): bool
    {
        // Kiểm tra trạng thái có hợp lệ không
        if (!self::isValidStatus($currentStatus) || !self::isValidStatus($newStatus)) {
            return false;
        }

        // Nếu cùng trạng thái thì không cần chuyển
        if ($currentStatus === $newStatus) {
            return false;
        }

        // Kiểm tra xem có trong danh sách chuyển trạng thái hợp lệ không
        $allowedNextStatuses = self::$allowedTransitions[$currentStatus] ?? [];
        return in_array($newStatus, $allowedNextStatuses);
    }

    /**
     * Kiểm tra trạng thái có hợp lệ không
     * 
     * @param string $status
     * @return bool
     */
    public static function isValidStatus(string $status): bool
    {
        return in_array($status, [
            self::STATUS_PENDING,
            self::STATUS_ASSIGNED,
            self::STATUS_STAFF_DONE,
            self::STATUS_WAITING_ADMIN,
            self::STATUS_ADMIN_APPROVED,
            self::STATUS_ADMIN_REJECTED,
        ]);
    }

    /**
     * Lấy danh sách trạng thái có thể chuyển đến từ trạng thái hiện tại
     * 
     * @param string $currentStatus
     * @return array
     */
    public static function getAllowedNextStatuses(string $currentStatus): array
    {
        return self::$allowedTransitions[$currentStatus] ?? [];
    }

    /**
     * Lấy tất cả các trạng thái hợp lệ
     * 
     * @return array
     */
    public static function getAllStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_ASSIGNED,
            self::STATUS_STAFF_DONE,
            self::STATUS_WAITING_ADMIN,
            self::STATUS_ADMIN_APPROVED,
            self::STATUS_ADMIN_REJECTED,
        ];
    }

    /**
     * Kiểm tra trạng thái có phải là trạng thái cuối không (không thể chuyển tiếp)
     * 
     * @param string $status
     * @return bool
     */
    public static function isFinalStatus(string $status): bool
    {
        return empty(self::$allowedTransitions[$status] ?? []);
    }
}

