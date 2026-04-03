<?php

namespace App\Models;

use App\Enum\ActiveStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class SchoolWorkingCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'working_days',
        'working_hours_start',
        'working_hours_end',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'working_days' => 'array',
        'working_hours_start' => 'datetime:H:i',
        'working_hours_end' => 'datetime:H:i',
        'is_active' => ActiveStatus::class,
    ];

    /**
     * Boot the model and apply the global scope.
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
     * Define a many-to-many relationship with the School model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
