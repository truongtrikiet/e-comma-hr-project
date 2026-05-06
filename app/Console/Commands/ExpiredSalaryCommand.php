<?php

namespace App\Console\Commands;

use App\Enum\ExpiredSalaryStatus;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpiredSalaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired-salary:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expired salaries to expired status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::today();

        $salaries = Salary::query()
            ->where('status', '!=', ExpiredSalaryStatus::EXPIRED->value)
            ->whereNotNull('effective_date')
            ->whereDate('effective_date', '<=', $now)
            ->get();

        foreach ($salaries as $salary) {
            $salary->update([
                'status' => ExpiredSalaryStatus::EXPIRED->value
            ]);
        }
        $this->info("Total {$salaries->count()} salaries updated to expired status");
    }
}
