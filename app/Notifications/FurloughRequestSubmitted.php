<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FurloughRequestSubmitted extends Notification
{
    use Queueable;

    protected $furlough;
    /**
     * Create a new notification instance.
     */
    public function __construct(
        $furlough
    ) {
        $this->furlough = $furlough;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'furlough_request_id' => $this->furlough->id,
            'message' => 'New furlough request requires approval',
            'action_url' => route(
                'admin.furlough.show',
                $this->furlough->id
            ),
        ];
    }
}
