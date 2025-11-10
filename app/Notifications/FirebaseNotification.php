<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Firebase Cloud Messaging Notification
 * 
 * Cần cài đặt package: composer require laravel-notification-channels/fcm
 * Sau đó uncomment các dòng liên quan đến FcmMessage và use statement
 * 
 * Cấu hình trong .env:
 * FCM_SERVER_KEY=your_firebase_server_key_here
 */
class FirebaseNotification extends Notification
{
    use Queueable;

    public $title;
    public $body;
    public $data;

    /**
     * Create a new notification instance.
     *
     * @param string $title
     * @param string $body
     * @param array $data Additional data payload
     */
    public function __construct($title, $body, array $data = [])
    {
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Kiểm tra xem có cài đặt FCM package không
        if (class_exists('\NotificationChannels\Fcm\FcmMessage')) {
            return ['fcm'];
        }
        
        // Fallback: Nếu chưa cài package, chỉ trả về database
        \Log::warning('FirebaseNotification: FCM package not installed. Falling back to database channel.');
        return ['database'];
    }

    /**
     * Get the FCM representation of the notification.
     * 
     * Uncomment sau khi cài đặt package laravel-notification-channels/fcm
     * và thêm use statement: use NotificationChannels\Fcm\FcmMessage;
     */
    /*
    public function toFcm($notifiable)
    {
        $message = FcmMessage::create()
            ->setNotification([
                'title' => $this->title,
                'body' => $this->body,
            ]);

        // Thêm data payload nếu có
        if (!empty($this->data)) {
            $message->setData($this->data);
        }

        return $message;
    }
    */

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
            'type' => 'firebase_notification',
        ];
    }

    /**
     * Get the database representation of the notification (fallback).
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}
