<?php

namespace App\Models;

use App\Enum\ContractStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\Contract
 */
class Contract extends Model
{
    use HasFactory;

    const PREFIX_CODE = 'HD';

    protected $fillable = [
        'code',
        'user_id',
        'school_id',
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

    public function contractType()
    {
        return $this->belongsTo(ContractType::class);
    }

    /**
     * Get the parent contractable model.
     */
    public function contractable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Define a many-to-many relationship with the ContractTypeAttribute model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function contractTypeAttributes(): BelongsToMany
    {
        return $this->belongsToMany(
            ContractTypeAttribute::class,
            'contract_attribute_values',
            'contract_id',
            'contract_type_attribute_id'
        );
    }

    /**
     * Get the appendix contracts associated with the contract.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appendixContracts()
    {
        return $this->belongsToMany(AppendixContract::class, 'contract_has_appendix_contracts');
    }

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

        // When signed_at or expired_at changes, automatically set status (but don't override CLEARED)
        static::saving(function (Contract $contract) {
            if ($contract->isDirty(['signed_at', 'expired_at']) || is_null($contract->status)) {
                if (!($contract->status instanceof ContractStatus && $contract->status === ContractStatus::CLEARED)) {
                    $contract->status = $contract->calculated_status;
                }
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
     * Use `code` for route model binding instead of `id`.
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    /**
     * Calculate the contract status based on signed and expired dates.
     * - UNDER_ACCEPTANCE when not signed yet
     * - ACTIVE when signed and not expired
     * - COMPLETED when expired
     * Note: `CLEARED` is considered a manual state and will not be overridden by automatic calc.
     */
    public function getCalculatedStatusAttribute(): ContractStatus
    {
        $now = Carbon::now();

        if (is_null($this->signed_at)) {
            return ContractStatus::UNDER_ACCEPTANCE;
        }

        if (!is_null($this->expired_at) && $this->expired_at instanceof \DateTime && $this->expired_at->lt($now)) {
            return ContractStatus::COMPLETED;
        }

        return ContractStatus::ACTIVE;
    }

    /**
     * Recalculate the stored `status` column and persist if different.
     * Will not override `CLEARED` unless $force is true.
     *
     * @param bool $force
     * @return ContractStatus
     */
    public function recalculateStatus(bool $force = false): ContractStatus
    {
        $calculated = $this->calculated_status;

        if (!$force && $this->status instanceof ContractStatus && $this->status === ContractStatus::CLEARED) {
            return $this->status;
        }

        $currentValue = $this->status instanceof ContractStatus ? $this->status->value : $this->status;
        if ($currentValue !== $calculated->value) {
            Model::withoutEvents(function () use ($calculated) {
                $this->status = $calculated;
                $this->save();
            });
        }

        return $calculated;
    }
}
