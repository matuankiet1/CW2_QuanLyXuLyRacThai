<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class TemplateNotificationService
{
    /**
     * Gửi thông báo sử dụng template
     *
     * @param int $userId
     * @param string $templateKey
     * @param array $variables
     * @return \App\Models\SimpleNotification|null
     */
    public static function send($userId, $templateKey, $variables = [])
    {
        try {
            $template = NotificationTemplate::where('key', $templateKey)->first();

            if (!$template) {
                Log::warning("TemplateNotificationService: Template not found", [
                    'template_key' => $templateKey,
                    'user_id' => $userId
                ]);
                return null;
            }

            $title = self::replaceVars($template->title, $variables);
            $content = self::replaceVars($template->content, $variables);

            return NotificationService::send($userId, $title, $content);
        } catch (\Exception $e) {
            Log::error("TemplateNotificationService: Failed to send notification", [
                'user_id' => $userId,
                'template_key' => $templateKey,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Gửi thông báo template đến nhiều users
     *
     * @param array $userIds
     * @param string $templateKey
     * @param array $variables
     * @return int Số lượng thông báo đã gửi thành công
     */
    public static function sendToMany(array $userIds, $templateKey, $variables = [])
    {
        $successCount = 0;
        
        foreach ($userIds as $userId) {
            $result = self::send($userId, $templateKey, $variables);
            if ($result) {
                $successCount++;
            }
        }

        return $successCount;
    }

    /**
     * Thay thế các biến trong template
     * Hỗ trợ cả {{key}} và {{{key}}}
     *
     * @param string $text
     * @param array $vars
     * @return string
     */
    private static function replaceVars($text, $vars)
    {
        foreach ($vars as $key => $value) {
            // Hỗ trợ cả {{key}} và {{{key}}}
            $text = str_replace("{{{$key}}}", $value, $text);
            $text = str_replace("{{$key}}", $value, $text);
        }
        return $text;
    }

    /**
     * Validate template variables
     *
     * @param string $templateKey
     * @param array $variables
     * @return array Missing variables
     */
    public static function validateVariables($templateKey, $variables)
    {
        $template = NotificationTemplate::where('key', $templateKey)->first();
        
        if (!$template) {
            return [];
        }

        // Tìm tất cả các biến trong template ({{key}} hoặc {{{key}}})
        preg_match_all('/\{\{\{?(\w+)\}\}\}/', $template->title . $template->content, $matches);
        
        $requiredVars = array_unique($matches[1] ?? []);
        $missingVars = array_diff($requiredVars, array_keys($variables));

        return $missingVars;
    }
}
