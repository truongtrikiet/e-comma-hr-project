<?php

namespace App\Models;

use App\Enum\ActiveStatus;
use App\Enum\IsPaid;
use App\Enum\MonthEnum;
use App\Enum\ResetTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;


class FurloughPolicy extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'school_id',
        'employee_type_id',
        'furlough_type_id',
        'furlough_policy_template_id',
        'accrual_per_month',
        'max_days',
        'carry_forward',
        'is_paid',
        'reset_type',
        'reset_month',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_paid' => IsPaid::class,
        'carry_forward' => 'boolean',
        'reset_type' => ResetTypeEnum::class,
        'reset_month' => MonthEnum::class,
        'status' => ActiveStatus::class,
    ];

    /**
     * Boot the model and apply the global scope.
     */
    protected static function booted()
    {
        static::addGlobalScope('school', function (Builder $builder) {
            $sessionSchoolName = session('school_name');
            $envSchoolName = config('subdomain.system_main');

            if (is_null($sessionSchoolName) || is_null($envSchoolName)) {
                return;
            }

            if ($sessionSchoolName !== $envSchoolName) {
                $builder->where('school_id', session('school_id'));
            }
        });
    }

    /**
     * Define a relationship to the School model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // /**
    //  * Define a relationship to the EmployeeTypes model.
    //  * 
    //  * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    //  */
    // public function employeeTypes()
    // {
    //     return $this->belongsToMany(
    //         EmployeeType::class, 
    //         'employee_type_furlough_policies',
    //         'furlough_policy_id', 
    //         'employee_type_id'
    //     )->using(EmployeeTypeFurloughPolicy::class);
    // }

    /**
    * Define a relationship to the EmployeeType model.
    * 
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function employeeType()
    {
        return $this->belongsTo(EmployeeType::class, 'employee_type_id');
    }

    /**
     * Define a relationship to the FurloughType model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function furloughType()
    {
        return $this->belongsTo(FurloughType::class);
    }

    /**
     * Define a relationship to the FurloughPolicyTemplate model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function furloughPolicyTemplate()
    {
        return $this->belongsTo(FurloughPolicyTemplate::class);
    }
}
