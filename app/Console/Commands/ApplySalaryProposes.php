<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SalaryPropose;
use App\Models\Salary;
use App\Enum\SalaryStatus;
use App\Enum\ExpiredSalaryStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ApplySalaryProposes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salary_proposes:apply';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply approved salary proposes whose effective_date has arrived';

    public function handle()
    {
        $today = Carbon::today();

        $proposes = SalaryPropose::query()
            ->where('status', SalaryStatus::APPROVED->value)
            ->where('is_applied', false)
            ->whereNotNull('effective_date')
            ->whereDate('effective_date', '<=', $today)
            ->get();

        $this->info('Found ' . $proposes->count() . ' proposes to apply');

        foreach ($proposes as $propose) {
            try {
                $exists = Salary::where('user_id', $propose->user_id)
                    ->whereDate('effective_date', Carbon::parse($propose->effective_date)->toDateString())
                    ->exists();

                if ($exists) {
                    $this->info("Skipping propose {$propose->id}: matching salary already exists");
                    $propose->is_applied = true;
                    $propose->save();
                    continue;
                }

                $current = Salary::where('user_id', $propose->user_id)
                    ->where('status', SalaryStatus::APPROVED->value)
                    ->orderByDesc('effective_date')
                    ->first();

                if ($current) {
                    $effectiveDate = Carbon::parse($propose->effective_date)->startOfDay();

                    if (is_null($current->ends_at) || Carbon::parse($current->ends_at)->gte($effectiveDate)) {
                        $current->ends_at = $effectiveDate->copy()->subDay()->toDateString();
                        $current->save();
                        $this->info("Adjusted ends_at of salary {$current->id} for user {$current->user_id}");
                    }
                }

                $taxPercent = $propose->proposed_tax_percent;
                if (is_null($taxPercent)) {
                    $taxPercent = Salary::where('user_id', $propose->user_id)
                        ->whereNotNull('tax_percent')
                        ->orderByDesc('effective_date')
                        ->value('tax_percent') ?? 0;
                }

                $gross = (float) $propose->proposed_gross_amount;
                $taxAmount = is_null($propose->proposed_tax_amount) ? round($gross * ((float)$taxPercent) / 100, 4) : $propose->proposed_tax_amount;
                $netAmount = is_null($propose->proposed_net_amount) ? round($gross - $taxAmount, 4) : $propose->proposed_net_amount;

                $salary = Salary::create([
                    'user_id' => $propose->user_id,
                    'school_id' => $propose->school_id ?? $propose->user?->school_id ?? null,
                    'gross_amount' => $propose->proposed_gross_amount,
                    'tax_percent' => $taxPercent,
                    'tax_amount' => $taxAmount,
                    'net_amount' => $netAmount,
                    'approved_at' => now(),
                    'effective_date' => $propose->effective_date,
                    'ends_at' => $propose->ends_at ?? null,
                    'status' => ExpiredSalaryStatus::ACTIVE->value,
                ]);

                $propose->is_applied = true;
                $propose->save();

                $this->info("Applied propose {$propose->id} -> created salary {$salary->id}");
            } catch (\Throwable $e) {
                Log::error('Failed applying salary propose ' . $propose->id . ': ' . $e->getMessage());
                $this->error('Failed applying propose ' . $propose->id . ': ' . $e->getMessage());
            }
        }

        return 0;
    }
}
