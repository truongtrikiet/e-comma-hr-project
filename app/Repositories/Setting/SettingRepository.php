<?php

namespace App\Repositories\Setting;

use App\Models\Setting;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * The repository for Setting Model
 */
class SettingRepository extends BaseRepository implements SettingRepositoryInterface
{
    const PER_PAGE = 10;

    /**
     * @inheritdoc
     */
    protected $model;

    /**
     * @inheritdoc
     */
    public function __construct(Setting $model)
    {
        $this->model = $model;
        parent::__construct($model);
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        return $this->model->first();
    }

    /**
     * @inheritdoc
     */
    public function findByKey(string $key): Model|null
    {
        return $this->model->where('key', $key)->first();
    }
}
