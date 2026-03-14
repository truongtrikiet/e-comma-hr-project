<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enum\ActiveStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Translatable\HasTranslations;

class EmployeeType extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'status'
    ];

    protected $casts = [
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
