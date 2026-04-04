<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Enum\FurloughStatus;
use App\Models\Furlough;

class FurloughRequestReviewed extends Notification
{
    use Queueable;

     protected FurloughStatus $status;
    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Furlough $furlough
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

    public function toDatabase($notifiable): array
    {
        $status = FurloughStatus::from($this->furlough->furlough_status->value);

        return [
            'type' => 'furlough_reviewed',
            'furlough_id' => $this->furlough->id,
            'status' => $status->value,
            'status_label' => FurloughStatus::getNameByValue($status->value),

            'action_url' => route(
                'staff.furlough.show',
                $this->furlough->id
            ),

            'message' => match ($status) {
                FurloughStatus::APPROVED => __('Your furlough request has been approved.'),
                FurloughStatus::REJECTED => __('Your furlough request has been rejected.'),
                default => __('Your furlough request status has been updated.'),
            },
        ];
    }
}
