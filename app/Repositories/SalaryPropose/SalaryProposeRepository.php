<?php

namespace App\Repositories\SalaryPropose;

use App\Models\SalaryPropose;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use App\Acl\Acl;

/**
 * The repository for the SalaryPropose Model
 */
class SalaryProposeRepository extends BaseRepository implements SalaryProposeRepositoryInterface
{
    const PER_PAGE = 20;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(SalaryPropose $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Paginating, ordering and searching through pages for server side index table for the Admin.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');
        $user_id = Arr::get($searchParams, 'user_id', null);
        $school_id = Arr::get($searchParams, 'school_id', null);
        $status = Arr::get($searchParams, 'status', null);
        $effective_date = Arr::get($searchParams, 'effective_date', null);
        $ends_at = Arr::get($searchParams, 'ends_at', null);
        $is_applied = Arr::get($searchParams, 'is_applied', null);

        $query = $this->model->query()->with(['user', 'school']);

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }

            $query->where(function ($q) use ($keyword) {
                $q->whereAny(['proposed_gross_amount', 'ends_at', 'effective_date',  'id'], 'LIKE', '%' . $keyword . '%')
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

        if (!is_null($is_applied)) {
            $query->where('is_applied', $is_applied);
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
        return $this->serverPaginationFiltering($searchParams);
    }

    /**
     * Override create to set user and school for staff creates
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();
            $data['school_id'] = auth()->user()?->school_id ?? session('school_id');

            $propose = $this->model->create($data);

            DB::commit();

            return $propose;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Override update to ensure user/school remain consistent
     */
    public function update($model, $data)
    {
        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();
            $data['school_id'] = auth()->user()?->school_id ?? session('school_id');

            $model->update($data);

            DB::commit();

            return $model;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
