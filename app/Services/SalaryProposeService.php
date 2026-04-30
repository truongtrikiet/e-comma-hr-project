<?php

namespace App\Services;

use App\Repositories\SalaryPropose\SalaryProposeRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Acl\Acl;
use App\Notifications\SalaryProposeApproved;
use App\Notifications\SalaryProposeReviewed;
use App\Models\Salary;
use App\Enum\SalaryStatus;
use Illuminate\Support\Facades\Log;

class SalaryProposeService
{
    public function __construct(
        protected SalaryProposeRepositoryInterface $salaryProposeRepository,
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

            if (empty($data['user_id'])) {
                $data['user_id'] = auth()->id();
            }

            $salaryPropose = $this->salaryProposeRepository->create($data);

            $hrRoles = User::query()
                ->where('school_id', $salaryPropose->school_id)
                ->whereHas('roles', fn ($q) => $q->where('name', Acl::ROLE_HR))
                ->where('id', '!=', auth()->id())
                ->get();

            foreach ($hrRoles as $hr) {
                $hr->notify(
                    new SalaryProposeApproved($salaryPropose)
                );
            }

            DB::commit();

            return $salaryPropose;
        } catch (\Exception $e) {
            DB::rollBack();
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

            if (empty($data['user_id'])) {
                $data['user_id'] = auth()->id();
            }

            $model->update($data);

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Approved salary propose.
     */
    public function approved($model, $data)
    {
        try {
            DB::beginTransaction();

            $model->update($data);

            $model->refresh()->load('user');

            if ($model->user) {
                $model->user->notify(new SalaryProposeReviewed($model));
            }

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
