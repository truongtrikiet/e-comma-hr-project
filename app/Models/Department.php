<?php

namespace App\Models;

use App\Enum\DepartmentType;
use App\Enum\SettingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Department extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'type',
        'description',
        'status',
        'created_at',
        'updated_at',
        'parent_id',
        'school_id',
    ];

    protected $casts = [
        'type' => DepartmentType::class,
        'status' => SettingStatus::class,
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
     * Define a many-to-many relationship with the Department model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id', 'id');
    }

    /**
     * Define a many-to-many relationship with the Department model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id', 'id');
    }

    /**
     * Define an inverse one-to-many relationship with the School model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
