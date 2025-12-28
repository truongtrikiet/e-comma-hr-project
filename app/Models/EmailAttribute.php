<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmailAttribute
 *
 */
class EmailAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'key',
    ];
}
