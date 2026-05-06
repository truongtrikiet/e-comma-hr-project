<?php

namespace App\Notifications;

use App\Models\MeetingSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MeetingScheduleReminder extends Notification
{
    use Queueable;

    public function __construct(
        protected MeetingSchedule $meetingSchedule,
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
            'meeting_id' => $this->meetingSchedule->id,
            'message' => sprintf('Meeting "%s" starts at %s — starting soon (15 minutes)',
                $this->meetingSchedule->title,
                $this->meetingSchedule->start_time?->format('Y-m-d H:i:s') ?? '-'
            ),
            'action_url' => route('staff.meeting-schedule.show', $this->meetingSchedule->id),
        ];
    }
}
