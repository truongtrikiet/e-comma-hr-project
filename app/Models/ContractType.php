<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\ContractType
 */
class ContractType extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'content',
    ];

    /**
     * Apply global scope to restrict by session school when not on system main
     */
    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            $sessionSchoolName = session('school_name');
            $envSchoolName = config('subdomain.system_main');

            if (is_null($sessionSchoolName) || is_null($envSchoolName)) {
                return;
            }

            if ($sessionSchoolName !== $envSchoolName) {
                $builder->where('school_id', session('school_id'));
            }
        });
    }

    /**
     * Define a relationship to the School model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

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
    public function contractAttributes(): BelongsToMany
    {
        return $this->belongsToMany(
            ContractAttribute::class,
            'contract_type_attributes',
            'contract_type_id',
            'contract_attribute_id'
        );
    }

    /**
     * Define a one-to-many relationship with the ContractTypeAttribute model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contractTypeAttributes(): HasMany
    {
        return $this->hasMany(ContractTypeAttribute::class);
    }
}
