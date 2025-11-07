<?php

namespace App\Services;

use App\Models\SimpleNotification;

class NotificationService
{
    /**
     * Gửi thông báo đơn giản đến user
     *
     * @param int $userId
     * @param string $title
     * @param string $message
     * @return SimpleNotification
     */
    public static function send($userId, $title, $message)
    {
        return SimpleNotification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
        ]);
    }
}

