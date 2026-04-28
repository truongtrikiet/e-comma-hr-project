<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MeetingSchedule;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MeetingScheduleReminder;

class SendMeetingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meeting:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send meeting reminders 15 minutes before start';

    public function handle()
    {
        $target = now()->addMinutes(15);
        $windowStart = $target->copy()->startOfMinute();
        $windowEnd = $target->copy()->endOfMinute();

        $meetings = MeetingSchedule::whereBetween('start_time', [$windowStart, $windowEnd])
            ->where('status', '!=', \App\Enum\MeetingScheduleStatus::CANCELLED->value)
            ->get();

        if ($meetings->isEmpty()) {
            $this->info('No meetings to remind at ' . $target->toDateTimeString());
            return 0;
        }

        foreach ($meetings as $meeting) {
            try {
                $recipients = collect();

                foreach ($meeting->targets as $t) {
                    $type = $t->target_type?->value ?? $t->target_type;
                    $id = $t->target_id;

                    switch ((int) $type) {
                        case \App\Enum\MeetingTargetType::USER->value:
                            $u = \App\Models\User::find($id);
                            if ($u) $recipients->push($u);
                            break;
                        case \App\Enum\MeetingTargetType::DEPARTMENT->value:
                            $users = \App\Models\User::where('department_id', $id)->get();
                            $recipients = $recipients->merge($users);
                            break;
                        case \App\Enum\MeetingTargetType::SCHOOL->value:
                            $users = \App\Models\User::where('school_id', $meeting->school_id)->get();
                            $recipients = $recipients->merge($users);
                            break;
                    }
                }

                $recipients = $recipients->unique('id')->filter();

                if ($recipients->isNotEmpty()) {
                    Notification::send($recipients, new MeetingScheduleReminder($meeting));
                }
            } catch (\Exception $e) {
                $this->error('Error reminding for meeting ' . $meeting->id . ': ' . $e->getMessage());
            }
        }

        return 0;
    }
}
