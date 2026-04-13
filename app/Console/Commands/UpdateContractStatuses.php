<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contract;
use App\Enum\ContractStatus;
use Illuminate\Database\Eloquent\Model;

class UpdateContractStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contracts:update-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate contract statuses based on signed_at and expired_at';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting contract status recalculation...');

        Contract::chunkById(200, function ($contracts) {
            foreach ($contracts as $contract) {
                $calculated = $contract->calculated_status;
                $current = $contract->status instanceof ContractStatus ? $contract->status->value : $contract->status;

                if ($current !== $calculated->value) {
                    Model::withoutEvents(function () use ($contract, $calculated) {
                        $contract->status = $calculated;
                        $contract->save();
                    });
                }
            }
        });

        $this->info('Contract status recalculation finished.');

        return self::SUCCESS;
    }
}
