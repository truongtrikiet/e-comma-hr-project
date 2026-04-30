<?php

namespace App\Repositories\Salary;

use App\Enum\ExpiredSalaryStatus;
use App\Enum\SalaryStatus;
use App\Models\Salary;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use App\Acl\Acl;

/**
 * The repository for the Salary Model
 */
class SalaryRepository extends BaseRepository implements SalaryRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(Salary $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringForAdmin($searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');
        $user_id = Arr::get($searchParams, 'user_id', null);
        $school_id = Arr::get($searchParams, 'school_id', null);
        $status = Arr::get($searchParams, 'status', null);
        $effective_date = Arr::get($searchParams, 'effective_date', null);
        $ends_at = Arr::get($searchParams, 'ends_at', null);

        $query = $this->model->query()->with('user');

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }

            $query->where(function ($q) use ($keyword) {
                $q->whereAny(['gross_amount', 'approved_at', 'effective_date', 'tax_percent', 'id'], 'LIKE', '%' . $keyword . '%')
                    ->orWhereHas('user', function ($uq) use ($keyword) {
                        $uq->where('name', 'LIKE', '%' . $keyword . '%')
                           ->orWhere('email', 'LIKE', '%' . $keyword . '%');
                    });
            });
        }

        if (! is_null($user_id)) {
            $query->where('user_id', $user_id);
        }

        if (! is_null($school_id)) {
            $query->where('school_id', $school_id);
        }

        if (! is_null($status)) {
            $query->where('status', $status);
        }

        if (!is_null($effective_date)) {
            $query->where('effective_date', '>=', $effective_date);
        }

        if (!is_null($ends_at)) {
            $query->where('ends_at', '<=', $ends_at);
        }

        $query->latest();

        return $query->paginate($limit);
    }

    /**
     * Paginating, ordering and searching through pages for server side index table by self.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFilteringByStaff($searchParams): LengthAwarePaginator
    {
        $searchParams['user_id'] = auth()->id();
        return $this->serverPaginationFilteringForAdmin($searchParams);
    }
    
    public function getCurrentSalary($userId)
    {
        $salary = $this->model->where('user_id', $userId)->latest()->first();
        return $salary ? $salary->gross_amount : 0;
    }

    /**
     * Override create method.
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();
            $data['status'] = ExpiredSalaryStatus::ACTIVE->value;

            $salary = $this->model->create($data);

            DB::commit();
            return $salary;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create salary: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Override update method.
     */
    public function update($model, $data)
    {
        try {
            DB::beginTransaction();
            
            $model->update($data);

            DB::commit();
            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update salary: ' . $e->getMessage());
            return null;
        }
    }
}
