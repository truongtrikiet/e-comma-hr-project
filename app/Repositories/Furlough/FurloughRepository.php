<?php

namespace App\Repositories\Furlough;

use App\Models\Furlough;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Acl\Acl;

/**
 * The repository for the Furlough Model
 */
class FurloughRepository extends BaseRepository implements FurloughRepositoryInterface
{
    const PER_PAGE = 20;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(Furlough $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * Paginating, ordering and searching through pages for server side index table.
     *
     * @param $searchParams
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function serverPaginationFiltering($searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');
        $user_id = Arr::get($searchParams, 'user_id', null);
        $furlough_type_id = Arr::get($searchParams, 'furlough_type_id', null);
        $school_id = Arr::get($searchParams, 'school_id', null);
        $duration_type = Arr::get($searchParams, 'duration_type', null);
        $half_day_session = Arr::get($searchParams, 'half_day_session', null);
        $furlough_status = Arr::get($searchParams, 'furlough_status', null);
        $start_time = Arr::get($searchParams, 'start_time', null);
        $end_time = Arr::get($searchParams, 'end_time', null);

        $query = $this->model->query()->with([
            'user', 
            'furloughType', 
            'school'
        ]);

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }
            $query->where(function ($q) use ($keyword) {
                $q->where('reason', 'LIKE', '%' . $keyword . '%')
                  ->orWhereHas('user', function ($uq) use ($keyword) {
                      $uq->where('name', 'LIKE', '%' . $keyword . '%');
                  })
                  ->orWhereHas('furloughType', function ($fq) use ($keyword) {
                      $fq->where('name', 'LIKE', '%' . $keyword . '%');
                  });
            });
        }

        if (!is_null($user_id)) {
            $query->where('user_id', $user_id);
        }

        if (!is_null($furlough_type_id)) {
            $query->where('furlough_type_id', $furlough_type_id);
        }

        if (!is_null($school_id)) {
            $query->where('school_id', $school_id);
        }

        if (!is_null($duration_type)) {
            $query->where('duration_type', $duration_type);
        }

        if (!is_null($half_day_session)) {
            $query->where('half_day_session', $half_day_session);
        }

        if (!is_null($start_time)) {
            $query->where('start_time', '>=', $start_time);
        }

        if (!is_null($end_time)) {
            $query->where('end_time', '<=', $end_time);
        }

        if (!is_null($furlough_status)) {
            $query->where('furlough_status', $furlough_status);
        }

        // $user = auth()->user();
        // if ($user && ($user->roles()->where('name', Acl::ROLE_STAFF)->exists() || $user->roles()->where('name', Acl::ROLE_TEACHER)->exists())) {
        //     $query->where('user_id', $user->id);
        // }

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
     * Override create method.
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();
            $data['school_id'] = auth()->user()?->school_id ?? session('school_id');
            
            $furlough = $this->model->create($data);

            DB::commit();

            return $furlough;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating furlough: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Override update method.
     */
    public function update($furlough, $data)
    {
        try {
            DB::beginTransaction();

            $data['user_id'] = auth()->id();
            $data['school_id'] = auth()->user()?->school_id ?? session('school_id');

            $furlough->update($data);

            DB::commit();

            return $furlough;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating furlough: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Approved furlough request.
     */
    public function approved($furlough, $data)
    {
        try {
            DB::beginTransaction();;

            $furlough->update($data);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error approving furlough: ' . $e->getMessage());
            throw $e;
        }
    }
}
