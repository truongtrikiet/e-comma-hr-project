<?php

namespace App\Notifications;

use App\Models\Furlough;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FurloughRequestSubmitted extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Furlough $furlough,
    ) {
        //
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
        $user = $this->furlough->user;

        return [
            'furlough_request_id' => $this->furlough->id,
            'message' => $user->name . ' has submitted a furlough request.',
            'action_url' => route(
                'hr.furlough.show',
                $this->furlough->id
            ),
        ];
    }
}
