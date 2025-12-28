<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\ContractType
 *
 */
class ContractType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
    ];

    /**
     * Define a one-to-many relationship with the Contract model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Define a many-to-many relationship with the ContractAttribute model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    // public function contractAttributes(): BelongsToMany
    // {
    //     return $this->belongsToMany(
    //         ContractAttribute::class,
    //         'contract_type_attributes',
    //         'contract_type_id',
    //         'contract_attribute_id'
    //     );
    // }

    /**
     * Define a one-to-many relationship with the ContractTypeAttribute model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function contractTypeAttributes(): HasMany
    // {
    //     return $this->hasMany(ContractTypeAttribute::class);
    // }
}
