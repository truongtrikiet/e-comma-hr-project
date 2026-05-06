<?php

namespace App\Console\Commands;

use App\Enum\ContractStatus;
use Illuminate\Console\Command;
use App\Models\Contract;
use Carbon\Carbon;

class ExpiredContractCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired-contract:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update expired contracts to expired status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::today();

        $exclude = [
            ContractStatus::COMPLETED->value,
            ContractStatus::UNDER_ACCEPTANCE->value,
            ContractStatus::CLEARED->value,
        ];

        $count = 0;

        Contract::whereNotIn('status', $exclude)
            ->whereNotNull('expired_at')
            ->whereDate('expired_at', '<=', $now)
            ->chunkById(200, function ($contracts) use (&$count) {
                foreach ($contracts as $contract) {
                    $contract->update([
                        'status' => ContractStatus::COMPLETED->value
                    ]);
                    $count++;
                }
            });

        $this->info("Total {$count} contracts updated to expired status");
    }
}
