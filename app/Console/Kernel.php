<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('holiday:remind')->dailyAt('11:00');
        $schedule->command('furlough:accrue')->monthlyOn(1, '00:05');
        // $schedule->command('contracts:update-statuses')->dailyAt('00:00');
        $schedule->command('meeting:remind')->everyMinute();
        $schedule->command('salary_proposes:apply')->daily();
        $schedule->command('expired-salary:update')->dailyAt('00:00');
        $schedule->command('expired-contract:update')->dailyAt('00:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
