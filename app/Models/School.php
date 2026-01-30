<?php

namespace App\Models;

use App\Enum\SslStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'sub_domain',
        'code_school',
        'ssl_status',
    ];

    protected $casts = [
        'ssl_status' => SslStatus::class,
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
