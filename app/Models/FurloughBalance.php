<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class FurloughBalance extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'user_id',
        'furlough_type_id',
        'total_days',
        'used_days',
        'remaining_days',
        'last_accrual_at',
        'last_reset_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'last_accrual_at' => 'datetime',
        'last_reset_at' => 'datetime',
    ];

    /**
     * Relation to furlough type
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function furloughType()
    {
        return $this->belongsTo(FurloughType::class);
    }
}
