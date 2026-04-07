<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\Contract\ContractRepositoryInterface;
use App\Repositories\AppendixContract\AppendixContractRepositoryInterface;

class AppendixContractService
{
    /**
     * Summary of __construct
     *
     * @param AppendixContractRepositoryInterface $appendixContractRepository
     * @param ContractRepositoryInterface $contractRepository
     */
    public function __construct(
        protected AppendixContractRepositoryInterface $appendixContractRepository,
        protected ContractRepositoryInterface $contractRepository,
    ) {
        //
    }

    public function create($data)
    {
        try {
            DB::beginTransaction();

            $appendixContract = $this->appendixContractRepository->create($data);

            $contract = $this->contractRepository->find($data['contract_id']);
            $contract->appendixContracts()->attach($appendixContract->id);

            DB::commit();

            return $appendixContract;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
