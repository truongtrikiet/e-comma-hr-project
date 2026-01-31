<?php

namespace App\Models;

use App\Enum\SettingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Translatable\HasTranslations;

class Subject extends Model
{
    use HasFactory, HasTranslations;
    
    protected $fillable = [
        'name',
        'school_id',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'status' => SettingStatus::class,
    ];

    public $translatable = [
        'name',
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
     * An accessor to get the product name translated to the current locale
     *
     * @return string
     */
    public function localeName(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getTranslation('name', app()->getLocale())
        );
    }
}
