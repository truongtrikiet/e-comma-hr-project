<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Address
 *
 * @property-read Model|\Eloquent $morphable
 * @method static \Illuminate\Database\Eloquent\Builder|Address active()
 * @method static \Illuminate\Database\Eloquent\Builder|Address inactive()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Address query()
 * @mixin \Eloquent
 */
class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'address'
    ];

    public function morphable(): MorphTo
    {
        return $this->morphTo();
    }
}
