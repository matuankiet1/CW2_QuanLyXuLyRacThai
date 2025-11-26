<?php

namespace App\Services;

use App\Models\TrashRequest;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

/**
 * Service xử lý thông báo cho TrashRequest
 */
class TrashRequestNotificationService
{
    /**
     * Gửi thông báo cho tất cả admin khi có yêu cầu thu gom rác mới
     *
     * @param TrashRequest $trashRequest
     * @return void
     */
    public static function notifyAdminsNewRequest(TrashRequest $trashRequest): void
    {
        try {
            $student = $trashRequest->student;
            $admins = User::where('role', 'admin')->get();

            $title = "Yêu cầu thu gom rác mới";
            $message = "Sinh viên {$student->name} đã gửi yêu cầu thu gom rác tại {$trashRequest->location}. Loại rác: {$trashRequest->type}.";

            foreach ($admins as $admin) {
                NotificationService::send(
                    $admin->user_id,
                    $title,
                    $message
                );

                // Gửi email nếu user cho phép
                if ($admin->allowsEmailNotifications()) {
                    self::sendEmailNotification($admin, $title, $message);
                }
            }
        } catch (\Exception $e) {
            Log::error("TrashRequestNotificationService: Failed to notify admins", [
                'trash_request_id' => $trashRequest->request_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Gửi thông báo cho student khi admin duyệt yêu cầu
     *
     * @param TrashRequest $trashRequest
     * @param string $status 'approved' hoặc 'rejected'
     * @return void
     */
    public static function notifyStudentReviewResult(TrashRequest $trashRequest, string $status): void
    {
        try {
            $student = $trashRequest->student;
            
            if ($status === 'approved') {
                $title = "Yêu cầu thu gom rác đã được duyệt";
                $message = "Yêu cầu thu gom rác của bạn tại {$trashRequest->location} đã được admin duyệt thành công.";
            } else {
                $title = "Yêu cầu thu gom rác bị từ chối";
                $message = "Yêu cầu thu gom rác của bạn tại {$trashRequest->location} đã bị từ chối. ";
                if ($trashRequest->admin_notes) {
                    $message .= "Lý do: {$trashRequest->admin_notes}";
                }
            }

            NotificationService::send(
                $student->user_id,
                $title,
                $message
            );

            // Gửi email nếu user cho phép
            if ($student->allowsEmailNotifications()) {
                self::sendEmailNotification($student, $title, $message);
            }
        } catch (\Exception $e) {
            Log::error("TrashRequestNotificationService: Failed to notify student", [
                'trash_request_id' => $trashRequest->request_id,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Gửi email thông báo
     *
     * @param User $user
     * @param string $title
     * @param string $message
     * @return void
     */
    private static function sendEmailNotification(User $user, string $title, string $message): void
    {
        try {
            if (class_exists(\App\Mail\NotificationMail::class)) {
                Mail::to($user->email)->send(new \App\Mail\NotificationMail($title, $message, $user->name));
            }
        } catch (\Exception $e) {
            Log::error("TrashRequestNotificationService: Failed to send email", [
                'user_id' => $user->user_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Lấy số lượng thông báo chưa đọc về trash requests cho admin
     *
     * @param int $adminId
     * @return int
     */
    public static function getUnreadTrashRequestNotificationsCount(int $adminId): int
    {
        try {
            return \App\Models\SimpleNotification::where('user_id', $adminId)
                ->where('is_read', false)
                ->where(function($query) {
                    $query->where('title', 'like', '%Yêu cầu thu gom rác%')
                          ->orWhere('message', 'like', '%thu gom rác%');
                })
                ->count();
        } catch (\Exception $e) {
            Log::error("TrashRequestNotificationService: Failed to get unread count", [
                'admin_id' => $adminId,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
}

