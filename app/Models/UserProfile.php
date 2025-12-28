<?php

namespace App\Models;

use App\Enum\RelativeRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\UserProfile
 *
 */
class UserProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'identification_number',
        'bank_name',
        'bank_number',
        'personal_income_tax',
        'insurance_number',
        'relative_name',
        'relative_number',
        'birth_place',
        'company_entry_date',
        'education_level',
        'birth',
        'gender',
        'identification_date',
        'identification_place',
        'relative_role',
        'school_name',
        'field',
        'about',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'company_entry_date' => 'datetime',
        'birth_date' => 'datetime',
        'identification_date' => 'datetime',
        'birth' => 'datetime',
        'relative_role' => RelativeRole::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}