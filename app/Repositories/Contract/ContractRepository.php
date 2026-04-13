<?php

namespace App\Repositories\Contract;

use App\Enum\ContractStatus;
use App\Events\SendContractNotification;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * The repository for Contract Model
 */
class ContractRepository extends BaseRepository implements ContractRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * @inheritdoc
     */
    protected $model;

    /**
     * @inheritdoc
     */
    public function __construct(Contract $model)
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
        $schoolId = Arr::get($searchParams, 'school_id', null);
        $status = Arr::get($searchParams, 'status', null);

        $query = $this->model->query()->with(['contractable', 'contractType', 'school']);

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }
            $query->where(function ($q) use ($keyword) {
                $q->where('code', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!is_null($schoolId)) {
            $query->where('school_id', $schoolId);
        }

        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $query->latest();

        return $query->paginate($limit);
    }

     /**
     * Paginating, ordering and searching through pages for server side index table for the Staff.
     *
     * @param $searchParams
     * @return LengthAwarePaginator
     */
    public function serverPaginationFilteringForStaff($searchParams): LengthAwarePaginator
    {
        $limit = Arr::get($searchParams, 'limit', self::PER_PAGE);
        $keyword = Arr::get($searchParams, 'search', '');

        $query = $this->model->query()->with(['contractable', 'contractType']);

        $query = $query->where('contractable_id', auth()->id());

        if ($keyword) {
            if (is_array($keyword)) {
                $keyword = $keyword['value'];
            }

            $query->where(function ($q) use ($keyword) {
                $q->where('signed_at', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('expired_at', 'LIKE', '%' . $keyword . '%')
                    ->orWhereHas('contractType', function ($q) use ($keyword) {
                        $q->where('name', 'LIKE', '%' . $keyword . '%');
                    });
            });
        }

        $query->latest();

        return $query->paginate($limit);
    }

    /**
     * @inheritdoc
     */
    public function destroy($model)
    {
        try {
            DB::beginTransaction();

            $model->appendixContracts()->delete();

            $model->delete();

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function serverPaginationContractsForModel($model): LengthAwarePaginator
    {
        return $model->contracts()
            ->with(['contractable', 'contractType'])
            ->latest()
            ->paginate(self::PER_PAGE);
    }

    /**
     * @inheritdoc
     */
    public function getAppendixContractInArrayForContract(Contract $model, array $values): Collection
    {
        return $model->appendixContracts()->whereIn('appendix_contracts.id', $values)->get();
    }

    /**
     * @inheritdoc
     */
    public function getContractsByUser($user)
    {
        return $this->model->where('contractable_type', User::class)
            ->where('contractable_id', $user->id)
            ->get();
    }

    /**
     * @inheritdoc
     */
    public function getContractByIdAndUser(int $contractId, int $userId)
    {
        return $this->model->where('id', $contractId)
            ->where('contractable_type', User::class)
            ->where('contractable_id', $userId)
            ->first();
    }

}
