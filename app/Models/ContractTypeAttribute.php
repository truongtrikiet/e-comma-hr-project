<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractTypeAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_attribute_id',
        'contract_type_id',
    ];

    /**
     * Define a belongs-to relationship with the ContractAttribute model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contractAttribute(): BelongsTo
    {
        return $this->belongsTo(ContractAttribute::class);
    }
}
