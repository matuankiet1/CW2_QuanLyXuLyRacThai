<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Firebase Cloud Messaging Notification
 * 
 * Cần cài đặt package: composer require laravel-notification-channels/fcm
 * Sau đó uncomment các dòng liên quan đến FcmMessage
 */
class FirebaseNotification extends Notification
{
    use Queueable;

    public $title;
    public $body;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['fcm'];
    }

    /**
     * Get the FCM representation of the notification.
     * 
     * Uncomment sau khi cài đặt package laravel-notification-channels/fcm
     */
    /*
    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setNotification([
                'title' => $this->title,
                'body' => $this->body,
            ]);
    }
    */

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
        ];
    }
}
