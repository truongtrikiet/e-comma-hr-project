<?php

namespace App\Models;

use App\Enum\CandidateScreeningStatus;
use App\Enum\IsSuitableStatus;
use App\Enum\PositionTypeEnum;
use App\Traits\EmailNotifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CandidateScreening extends Model
{
    use HasFactory, EmailNotifiable;

    protected $table = 'candidate_screenings';

    protected $fillable = [
        'school_id',
        'ai_profile_id',
        'position_type',
        'candidate_name',
        'candidate_email',
        'candidate_phone_number',
        'resume_file_path',
        'ai_result_json',
        'is_suitable',
        'recommended_roles',
        'status',
        'screened_at',
        'emailed_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'position_type' => PositionTypeEnum::class,
        'ai_result_json' => 'array',
        'recommended_roles' => 'array',
        'is_suitable' => IsSuitableStatus::class,
        'status' => CandidateScreeningStatus::class,
        'screened_at' => 'datetime',
        'emailed_at' => 'datetime',
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
     * Define a relationship to the School model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Define a relationship to the AIProfile model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function aiProfile()
    {
        return $this->belongsTo(AIProfile::class);
    }
}
