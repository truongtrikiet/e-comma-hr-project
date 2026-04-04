<?php

namespace App\Models;

use App\Enum\ActiveStatus;
use App\Enum\IsPaid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FurloughPolicyTemplate extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'accrual_per_month',
        'max_days',
        'carry_forward',
        'is_paid',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'carry_forward' => 'boolean',
        'is_paid' => IsPaid::class,
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
