<?php

namespace App\Models;

use App\Enum\AppendixContractStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppendixContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => AppendixContractStatus::class,
    ];

    /**
     * Get the contracts associated with the appendix contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contracts()
    {
        return $this->belongsToMany(Contract::class, 'contract_has_appendix_contracts');
    }
}
