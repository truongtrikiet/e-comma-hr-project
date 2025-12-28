<?php

namespace App\Traits;

use App\Models\Address;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasOneAddress
{
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'morph');
    }
}
