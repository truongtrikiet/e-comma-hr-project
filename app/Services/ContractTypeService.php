<?php

namespace App\Services;

use App\Repositories\ContractType\ContractTypeRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use App\Models\School;

class ContractTypeService
{
    /**
     * Summary of __construct
     *
     * @param ContractTypeRepositoryInterface $contractTypeRepository
     */
    public function __construct(
        protected ContractTypeRepositoryInterface $contractTypeRepository,
    ) {
        //
    }

    /**
     * Override create method.
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $attributeIds = $data['contract_attribute_ids'] ?? [];

            $defaultSystem = School::where('sub_domain', env('SYSTEM_MAIN', 'ecs'))->first();
            $data['school_id'] = $data['school_id'] ?? ($defaultSystem->id ?? null);

            $contractType = $this->contractTypeRepository->create(
                Arr::except($data, ['contract_attribute_ids'])
            );

            if (!empty($attributeIds)) {
                $contractType->contractAttributes()->sync($attributeIds);
            }

            DB::commit();

            return $contractType;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Override update method.
     */
    public function update($model, $data)
    {
        try {
            DB::beginTransaction();

            $attributeIds = $data['contract_attribute_ids'] ?? [];

            $updatedModel = $this->contractTypeRepository->update(
                $model,
                Arr::except($data, ['contract_attribute_ids'])
            );

            if (!empty($attributeIds)) {
                $updatedModel->contractAttributes()->sync($attributeIds);
            }

            DB::commit();

            return $updatedModel;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating contract type: ' . $e->getMessage());
            throw $e;
        }
    }
}
