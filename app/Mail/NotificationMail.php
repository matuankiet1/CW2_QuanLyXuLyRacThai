<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $title;
    public $message;
    public $userName;

    /**
     * Create a new message instance.
     *
     * @param string $title
     * @param string $message
     * @param string|null $userName
     */
    public function __construct($title, $message, $userName = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->title)
            ->view('emails.notification')
            ->with([
                'title' => $this->title,
                'content' => $this->message, // Lê Tâm: Tôi sửa tên biến từ ''message thành 'content' để không gặp lỗi của Laravel, vì Laravel đã sử dụng biến message nội bộ rồi.
                'userName' => $this->userName,
            ]);
    }
}
