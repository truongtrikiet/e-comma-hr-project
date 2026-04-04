<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HolidayReminder extends Notification
{
    use Queueable;

    protected $holiday;

    public function __construct($holiday)
    {
        $this->holiday = $holiday;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'holiday_id' => $this->holiday->id,
            'message' => sprintf('Tomorrow is holiday: %s (from %s to %s)',
                $this->holiday->localeName->get(),
                $this->holiday->start_date->format('Y-m-d'),
                $this->holiday->end_date->format('Y-m-d')
            ),
            'action_url' => route('admin.holiday-schedule.index'),
        ];
    }
}
