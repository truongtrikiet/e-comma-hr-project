<?php

namespace App\Models;

use App\Enum\ActiveStatus;
use App\Enum\AIProviderEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class AIProfile extends Model
{
    use HasFactory;

    protected $table = 'ai_profiles';

    protected $fillable = [
        'name',
        'school_id',
        'provider',
        'api_key_encrypted',
        'model',
        'endpoint',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'provider' => AIProviderEnum::class,
        'status' => ActiveStatus::class,
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
}
