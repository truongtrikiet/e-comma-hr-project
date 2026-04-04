<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EmployeeTypeFurloughPolicy extends Pivot
{
    use HasFactory;

    protected $table = 'employee_type_furlough_policies';

    protected $fillable = [
        'employee_type_id',
        'furlough_policy_id',
    ];

    /**
     * Define a relationship to the EmployeeType model.
     */
    public function employeeType()
    {
        return $this->belongsTo(EmployeeType::class);
    }

    /**
     * Define a relationship to the FurloughPolicy model.
     */
    public function furloughPolicy()
    {
        return $this->belongsTo(FurloughPolicy::class);
    }
}
