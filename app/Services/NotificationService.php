<?php

namespace App\Services;

use App\Models\SimpleNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Gửi thông báo đơn giản đến user
     *
     * @param int $userId
     * @param string $title
     * @param string $message
     * @return SimpleNotification|null
     */
    public static function send($userId, $title, $message)
    {
        try {
            // Validate user exists
            $user = User::find($userId);
            if (!$user) {
                Log::warning("NotificationService: User not found", ['user_id' => $userId]);
                return null;
            }

            // Validate input
            if (empty($title) || empty($message)) {
                Log::warning("NotificationService: Title or message is empty", [
                    'user_id' => $userId,
                    'title' => $title,
                    'message' => $message
                ]);
                return null;
            }

            return SimpleNotification::create([
                'user_id' => $userId,
                'title' => trim($title),
                'message' => trim($message),
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            Log::error("NotificationService: Failed to send notification", [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Gửi thông báo đến nhiều users
     *
     * @param array $userIds
     * @param string $title
     * @param string $message
     * @return int Số lượng thông báo đã gửi thành công
     */
    public static function sendToMany(array $userIds, $title, $message)
    {
        $successCount = 0;
        
        foreach ($userIds as $userId) {
            $result = self::send($userId, $title, $message);
            if ($result) {
                $successCount++;
            }
        }

        return $successCount;
    }

    /**
     * Đánh dấu thông báo đã đọc
     *
     * @param int $notificationId
     * @param int $userId
     * @return bool
     */
    public static function markAsRead($notificationId, $userId)
    {
        try {
            $notification = SimpleNotification::where('id', $notificationId)
                ->where('user_id', $userId)
                ->first();

            if ($notification && !$notification->is_read) {
                $notification->update(['is_read' => true]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error("NotificationService: Failed to mark as read", [
                'notification_id' => $notificationId,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Đánh dấu tất cả thông báo đã đọc cho một user
     *
     * @param int $userId
     * @return int Số lượng thông báo đã được đánh dấu
     */
    public static function markAllAsRead($userId)
    {
        try {
            return SimpleNotification::where('user_id', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } catch (\Exception $e) {
            Log::error("NotificationService: Failed to mark all as read", [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
}
