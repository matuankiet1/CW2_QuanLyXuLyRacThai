<?php

namespace App\Services;

use App\Models\User;
use App\Models\NotificationPreference;
use App\Mail\NotificationMail;
use App\Notifications\FirebaseNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class IntegratedNotificationService
{
    /**
     * Gửi thông báo tích hợp (in-app + email + push) dựa trên preferences của user
     *
     * @param int $userId
     * @param string $title
     * @param string $message
     * @param array $options
     * @return array Kết quả gửi thông báo
     */
    public static function send($userId, $title, $message, array $options = [])
    {
        $user = User::find($userId);
        
        if (!$user) {
            Log::warning("IntegratedNotificationService: User not found", ['user_id' => $userId]);
            return self::createResult(false, 'User not found');
        }

        // Lấy preferences hoặc tạo mới với giá trị mặc định
        $preference = $user->preference ?? NotificationPreference::updateOrCreateForUser($userId);
        
        $results = [
            'in_app' => false,
            'email' => false,
            'push' => false,
        ];

        // 1. Gửi in-app notification (luôn gửi)
        if ($preference->allowsInApp() || !isset($options['skip_in_app'])) {
            $inAppResult = NotificationService::send($userId, $title, $message);
            $results['in_app'] = $inAppResult !== null;
        }

        // 2. Gửi email notification
        if ($preference->allowsEmail() && isset($user->email)) {
            try {
                Mail::to($user->email)->send(new NotificationMail($title, $message, $user->name));
                $results['email'] = true;
            } catch (\Exception $e) {
                Log::error("IntegratedNotificationService: Failed to send email", [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // 3. Gửi push notification
        if ($preference->allowsPush()) {
            try {
                $user->notify(new FirebaseNotification($title, $message));
                $results['push'] = true;
            } catch (\Exception $e) {
                Log::error("IntegratedNotificationService: Failed to send push notification", [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return self::createResult(true, 'Notifications sent', $results);
    }

    /**
     * Gửi thông báo tích hợp đến nhiều users
     *
     * @param array $userIds
     * @param string $title
     * @param string $message
     * @param array $options
     * @return array Thống kê kết quả
     */
    public static function sendToMany(array $userIds, $title, $message, array $options = [])
    {
        $stats = [
            'total' => count($userIds),
            'success' => 0,
            'failed' => 0,
            'details' => [],
        ];

        foreach ($userIds as $userId) {
            $result = self::send($userId, $title, $message, $options);
            
            if ($result['success']) {
                $stats['success']++;
            } else {
                $stats['failed']++;
            }
            
            $stats['details'][$userId] = $result;
        }

        return $stats;
    }

    /**
     * Gửi thông báo template tích hợp
     *
     * @param int $userId
     * @param string $templateKey
     * @param array $variables
     * @param array $options
     * @return array
     */
    public static function sendTemplate($userId, $templateKey, array $variables = [], array $options = [])
    {
        $template = \App\Models\NotificationTemplate::findByKey($templateKey);
        
        if (!$template) {
            return self::createResult(false, "Template '{$templateKey}' not found");
        }

        // Validate variables
        $missingVars = $template->validateVariables($variables);
        if (!empty($missingVars)) {
            return self::createResult(false, 'Missing variables: ' . implode(', ', $missingVars));
        }

        $rendered = $template->render($variables);
        
        return self::send($userId, $rendered['title'], $rendered['content'], $options);
    }

    /**
     * Tạo kết quả chuẩn
     *
     * @param bool $success
     * @param string $message
     * @param mixed $data
     * @return array
     */
    private static function createResult($success, $message, $data = null)
    {
        $result = [
            'success' => $success,
            'message' => $message,
        ];

        if ($data !== null) {
            $result['data'] = $data;
        }

        return $result;
    }
}

