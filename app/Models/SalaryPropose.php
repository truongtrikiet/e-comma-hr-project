<?php

namespace App\Models;

use App\Enum\SalaryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

class SalaryPropose extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'school_id',
        'proposed_gross_amount',
        'proposed_tax_percent',
        'proposed_tax_amount',
        'proposed_net_amount',
        'reason',
        'effective_date',
        'status',
        'is_applied',
        'ends_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status' => SalaryStatus::class,
        'effective_date' => 'datetime',
        'is_applied' => 'boolean',
        'ends_at' => 'datetime',
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
