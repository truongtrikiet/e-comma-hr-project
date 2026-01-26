<?php

namespace App\Models;

use App\Enum\DepartmentType;
use App\Enum\SettingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'type',
        'description',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'type' => DepartmentType::class,
        'status' => SettingStatus::class,
    ];
}
