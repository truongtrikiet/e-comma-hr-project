<?php

namespace App\Http\Requests\SalaryPropose;

use Illuminate\Foundation\Http\FormRequest;
use App\Acl\Acl;
use App\Rules\ValidAmount;

class UpdateSalaryProposeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_SALARY_PROPOSE_EDIT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'nullable',
            ],
            'school_id' => [
                'nullable',
            ],
            'proposed_gross_amount' => [
                'required', 
                'numeric', 
                new ValidAmount
            ],
            'reason' => [
                'nullable',
                'string',
                'max:255',
            ],
            'effective_date' => [
                'required',
            ],
            'ends_at' => [
                'required',
            ],
        ];
    }
}
