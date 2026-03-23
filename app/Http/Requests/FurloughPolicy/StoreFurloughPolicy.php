<?php

namespace App\Http\Requests\FurloughPolicy;

use App\Acl\Acl;
use Illuminate\Foundation\Http\FormRequest;
use App\Enum\ActiveStatus;
use App\Enum\IsPaid;
use Illuminate\Validation\Rules\Enum;
use App\Enum\ResetTypeEnum;
use App\Enum\MonthEnum;

class StoreFurloughPolicy extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_FURLOUGH_POLICY_ADD);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'school_id' => [
                'nullable',
                'integer',
                'exists:schools,id'
            ],
            'employee_type_id' => [
                'required',
                'integer',
                'exists:employee_types,id'
            ],
            'furlough_type_id' => [
                'required',
                'integer',
                'exists:furlough_types,id'
            ],
            'furlough_policy_template_id' => [
                'nullable',
                'integer',
                'exists:furlough_policy_templates,id'
            ],
            'accrual_per_month' => [
                'nullable',
                'numeric',
                'min:0',
                'max:31',
            ],
            'max_days' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'carry_forward' => [
                'nullable',
                'boolean',
            ],
            'is_paid' => [
                'required', 
                new Enum(IsPaid::class)
            ],
            'reset_type' => [
                'required',
                new Enum(ResetTypeEnum::class)
            ],
            'reset_month' => [
                'nullable',
                new Enum(MonthEnum::class)
            ]
        ];
    }
}
