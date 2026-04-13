<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ContractAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
    ];

    /**
     * Define a many-to-many relationship with the ContractType model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contractTypes(): BelongsToMany
    {
        return $this->belongsToMany(
            ContractType::class,
            'contract_type_attributes',
            'contract_attribute_id',
            'contract_type_id',
        );
    }
}
