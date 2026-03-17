<?php

namespace App\Models;

use App\Enum\ActiveStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Translatable\HasTranslations;

class HolidaySchedule extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'total_days',
        'status',
        'created_by',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => ActiveStatus::class,
    ];

    public $translatable = [
        'name',
    ];

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
