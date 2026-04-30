<?php

namespace App\Models;

use App\Enum\ExpiredSalaryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enum\SalaryStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Salary extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'user_id',
        'school_id',
        'gross_amount',
        'tax_percent',
        'tax_amount',
        'net_amount',
        'approved_at',
        'effective_date',
        'ends_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => ExpiredSalaryStatus::class,
        'approved_at' => 'datetime',
        'effective_date' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Whether this salary is currently active (based on effective_date and ends_at)
     */
    public function getIsActiveAttribute(): bool
    {
        $now = now();

        if ($this->status?->value !== ExpiredSalaryStatus::ACTIVE->value) {
            return false;
        }

        if ($this->effective_date && $this->effective_date->greaterThan($now)) {
            return false;
        }

        if ($this->ends_at && $this->ends_at->lessThan($now)) {
            return false;
        }

        return true;
    }

    /**
     * Whether this salary has expired (ends_at before now)
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->ends_at ? $this->ends_at->isPast() : false;
    }

    /**
     * Human readable period for the salary (e.g. "2026-04-01 — 2026-06-30" or "2026-04-01 — Present")
     */
    public function getPeriodAttribute(): string
    {
        $from = $this->effective_date?->format('Y-m-d') ?? 'N/A';
        $to = $this->ends_at?->format('Y-m-d') ?? __('general.common.present');

        return sprintf('%s — %s', $from, $to);
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
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Define the inverse of the one-to-one relationship with the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
