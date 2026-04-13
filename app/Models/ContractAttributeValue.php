<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContractAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'contract_type_attribute_id',
        'value',
    ];

    /**
     * Define a one-to-many relationship with the Contract model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Define a one-to-many relationship with the ContractTypeAttribute model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contractTypeAttribute(): BelongsTo
    {
        return $this->belongsTo(ContractTypeAttribute::class);
    }
}
