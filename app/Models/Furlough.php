<?php

namespace App\Models;

use App\Enum\DurationType;
use App\Enum\FurloughStatus;
use App\Enum\HalfDaySession;
use App\Enum\UseBalanceFurloughEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Furlough extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'furlough_type_id',
        'school_id',
        'reason',
        'duration_type',
        'half_day_session',
        'start_time',
        'end_time',
        'furlough_status',
        'number_of_days',
        'use_balance',
        'furlough_balance_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'duration_type' => DurationType::class,
        'half_day_session' => HalfDaySession::class,
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'furlough_status' => FurloughStatus::class,
        'use_balance' => UseBalanceFurloughEnum::class,
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
     * Define a relationship to the User model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define a relationship to the FurloughType model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function furloughType()
    {
        return $this->belongsTo(FurloughType::class);
    }

    /**
     * Define a relationship to the School model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Define a relationship to the FurloughBalance model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function furloughBalance()
    {
        return $this->belongsTo(FurloughBalance::class);
    }
}
