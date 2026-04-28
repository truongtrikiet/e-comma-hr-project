<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MeetingScheduleReminder extends Notification
{
    use Queueable;

    protected $meeting;

    public function __construct($meeting)
    {
        $this->meeting = $meeting;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'meeting_id' => $this->meeting->id,
            'message' => sprintf('Meeting "%s" starts at %s — starting soon (15 minutes)',
                $this->meeting->title,
                $this->meeting->start_time?->format('Y-m-d H:i:s') ?? '-'
            ),
            'action_url' => route('admin.meeting-schedule.show', $this->meeting->id),
        ];
    }
}
