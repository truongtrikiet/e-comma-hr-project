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

        $query = $this->model->query()->with(['user', 'school']);

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }

            $query->whereAny(['proposed_gross_amount', 'ends_at', 'effective_date',  'id'], 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('email', 'LIKE', '%' . $keyword . '%');
            });
        }

        // $user = auth()->user();
        // if ($user && ($user->roles()->where('name', Acl::ROLE_STAFF)->exists() || $user->roles()->where('name', Acl::ROLE_TEACHER)->exists())) {
        //     $query->where('user_id', $user->id);
        // }

        $query->latest();

        return $query->paginate($limit);
    }
}
