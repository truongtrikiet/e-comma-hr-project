<?php

namespace App\Models;

use App\Enum\ContractStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Contract
 *
 */
class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'contract_type_id',
        'contractable_id',
        'contractable_type',
        'status',
        'signed_at',
        'expired_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'signed_at' => 'datetime',
        'expired_at' => 'datetime',
        'status' => ContractStatus::class,
    ];

    /**
     * Get the parent contractable model.
     */
    public function contractable(): MorphTo
    {
        return $this->morphTo();
    }
}
