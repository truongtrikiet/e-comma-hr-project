<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MeetingScheduleCreated extends Notification
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
            'message' => sprintf('You have been invited to meeting: %s at %s',
                $this->meeting->title,
                $this->meeting->start_time?->format('Y-m-d H:i:s') ?? '-'
            ),
            'action_url' => route('admin.meeting-schedule.show', $this->meeting->id),
        ];
    }
}
