<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ThesisStatusUpdated extends Notification
{
    use Queueable;

    public $message;
    public $type;
    public $thesisId;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $type = 'info', $thesisId = null)
    {
        $this->message = $message;
        $this->type = $type;
        $this->thesisId = $thesisId;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
            'thesis_id' => $this->thesisId,
        ];
    }
}
