<?php

namespace App\Notifications;

use App\Models\HolidaySchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HolidayReminder extends Notification
{
    use Queueable;

    public function __construct(
        protected HolidaySchedule $holidaySchedule,
    ) {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'holiday_id' => $this->holidaySchedule->id,
            'message' => sprintf('Tomorrow is holiday: %s (from %s to %s)',
                $this->holidaySchedule->localeName->get(),
                $this->holidaySchedule->start_date->format('Y-m-d'),
                $this->holidaySchedule->end_date->format('Y-m-d')
            ),
            'action_url' => route('admin.holiday-schedule.index'),
        ];
    }
}
