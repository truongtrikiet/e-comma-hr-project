<?php

namespace App\Repositories\Setting;

use App\Repositories\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * The repository interface for the Setting Model
 */
interface SettingRepositoryInterface extends RepositoryInterface
{
    /**
     * Summary of findByKey
     *
     * @param string $key
     * @return Model|null
     */
    public function findByKey(string $key): Model|null;
}
