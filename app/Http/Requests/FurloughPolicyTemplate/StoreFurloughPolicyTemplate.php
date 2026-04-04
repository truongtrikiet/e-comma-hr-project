<?php

namespace App\Http\Requests\FurloughPolicyTemplate;

use App\Acl\Acl;
use App\Enum\ActiveStatus;
use App\Enum\IsPaid;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreFurloughPolicyTemplate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_FURLOUGH_POLICY_TEMPLATE_ADD);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'accrual_per_month' => [
                'required',
                'numeric',
                'min:1',
                'max:31',
            ],
            'max_days' => [
                'required',
                'numeric',
                'min:1',
            ],
            'carry_forward' => [
                'required',
                'boolean',
            ],
            'is_paid' => [
                'required', 
                new Enum(IsPaid::class)
            ],
        ];

        foreach (config('app.locales') as $locale) {
            $rules['name.' . $locale] = [
                'required',
                'string',
                'max:255'
            ];
        }

        return $rules;
    }
}
