<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enum\SalaryStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salary extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'user_id',
        'gross_amount',
        'tax_percent',
        'tax_amount',
        'net_amount',
        'approved_at',
        'effective_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => SalaryStatus::class,
        'approved_at' => 'datetime',
        'effective_date' => 'datetime',
    ];

    /**
     * Define the inverse of the one-to-one relationship with the User model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
