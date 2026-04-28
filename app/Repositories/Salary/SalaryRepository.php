<?php

namespace App\Repositories\Salary;

use App\Enum\SalaryStatus;
use App\Models\Salary;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

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

        $query = $this->model->query()->with('user');

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }

            $query->whereAny(['gross_amount', 'approved_at', 'effective_date', 'tax_percent', 'id'], 'LIKE', '%' . $keyword . '%')
                ->orWhereHas('user', function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('email', 'LIKE', '%' . $keyword . '%');
            });
        }

        $query->latest();

        return $query->paginate($limit);
    }
    
    public function getCurrentSalary($userId)
    {
        $salary = $this->model->where('user_id', $userId)->latest()->first();
        return $salary ? $salary->amount : 0;
    }

    /**
     * Override create method.
     */
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $data['status'] = SalaryStatus::APPROVED->value;
            
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
