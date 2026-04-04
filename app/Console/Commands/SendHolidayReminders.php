<?php

namespace App\Console\Commands;

use App\Enum\ActiveStatus;
use Illuminate\Console\Command;
use App\Models\HolidaySchedule;
use App\Models\User;
use App\Notifications\HolidayReminder;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class SendHolidayReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'holiday:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send holiday reminders to users one day before start_date.';

    public function handle()
    {
        $tomorrow = Carbon::today()->addDay()->toDateString();

        $holidays = HolidaySchedule::whereDate('start_date', $tomorrow)
            ->where('status', ActiveStatus::ACTIVE->value)
            ->get();

        if ($holidays->isEmpty()) {
            $this->info('No holiday starting tomorrow.');
            return 0;
        }

        $this->info('Found ' . $holidays->count() . ' holiday(s) starting tomorrow.');

        $chunkSize = 100;
        User::chunk($chunkSize, function ($users) use ($holidays) {
            foreach ($holidays as $holiday) {
                Notification::send($users, new HolidayReminder($holiday));
            }
        });

        $this->info('Holiday reminders sent.');
        return 0;
    }
}
