<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
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
                        'message' => $this->message,
                        'userName' => $this->userName,
                    ]);
    }
}
