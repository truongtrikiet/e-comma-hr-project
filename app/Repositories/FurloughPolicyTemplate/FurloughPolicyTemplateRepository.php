<?php

namespace App\Repositories\FurloughPolicyTemplate;

use App\Enum\ActiveStatus;
use App\Models\FurloughPolicyTemplate;
use App\Repositories\BaseRepository;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * The repository for the FurloughPolicyTemplate Model
 */
class FurloughPolicyTemplateRepository extends BaseRepository implements FurloughPolicyTemplateRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * {@inheritdoc}
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public function __construct(FurloughPolicyTemplate $model)
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
        $status = Arr::get($searchParams, 'status', null);

        $query = $this->model->query();

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $query->latest();

        return $query->paginate($limit);
    }

   /**
    * Override create function.
    */
    public function create($data)
    {
        try {
            DB::beginTransaction();

            $data['status'] = ActiveStatus::ACTIVE;

            $furloughPolicyTemplate = $this->model->create($data);

            DB::commit();
            return $furloughPolicyTemplate;
        } catch (\Exception $e) {
            Log::info('Creating FurloughPolicyTemplate failed: ' . $e->getMessage());
            DB::rollBack();
            return false;
        }
    }

   /**
    * Override update function.
    */
   public function update($model, $data)
   {
        try {
            DB::beginTransaction();

            $model->update($data);

            DB::commit();
            return $model;
        } catch (\Exception $e) {
            Log::info('Updating FurloughPolicyTemplate failed: ' . $e->getMessage());
            DB::rollBack();
            return false;
        }
   }

   /**
    * Get furlough policy templates active status.
    */
   public function getFurloughPolicyTemplate()
   {
       return $this->model->where('status', ActiveStatus::ACTIVE)->get();
   }
}