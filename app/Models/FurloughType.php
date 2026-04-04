<?php

namespace App\Models;

use App\Enum\ActiveStatus;
use App\Enum\IsPaid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Translatable\HasTranslations;

class FurloughType extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'status',
        'is_paid',
    ];

    protected $casts = [
        'status' => ActiveStatus::class,
        'is_paid' => IsPaid::class,
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
