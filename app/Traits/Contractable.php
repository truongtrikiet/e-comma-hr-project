<?php

namespace App\Traits;

use App\Models\Contract;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Contractable
{
    /**
     * Get the model's contract.
     */
    public function contract(): MorphOne
    {
        return $this->morphOne(Contract::class, 'contractable');
    }

    /**
     * Get all of the model's contracts.
     */
    public function contracts(): MorphMany
    {
        return $this->morphMany(Contract::class, 'contractable');
    }
}
