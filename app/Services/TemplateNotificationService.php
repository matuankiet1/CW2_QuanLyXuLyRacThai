<?php

namespace App\Services;

use App\Models\NotificationTemplate;
use App\Services\NotificationService;

class TemplateNotificationService
{
    /**
     * Gửi thông báo sử dụng template
     *
     * @param int $userId
     * @param string $templateKey
     * @param array $variables
     * @return \App\Models\SimpleNotification
     */
    public static function send($userId, $templateKey, $variables = [])
    {
        $template = NotificationTemplate::where('key', $templateKey)->firstOrFail();

        $title = self::replaceVars($template->title, $variables);
        $content = self::replaceVars($template->content, $variables);

        return NotificationService::send($userId, $title, $content);
    }

    /**
     * Thay thế các biến trong template
     *
     * @param string $text
     * @param array $vars
     * @return string
     */
    private static function replaceVars($text, $vars)
    {
        foreach ($vars as $key => $value) {
            $text = str_replace("{{{$key}}}", $value, $text);
        }
        return $text;
    }
}

