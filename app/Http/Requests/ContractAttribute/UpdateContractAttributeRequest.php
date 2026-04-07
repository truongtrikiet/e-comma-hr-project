<?php

namespace App\Http\Requests\ContractAttribute;

use App\Acl\Acl;
use App\Rules\LowercaseUnderscore;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateContractAttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_CONTRACT_ATTRIBUTE_EDIT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return  [
            'key' => [
                'required',
                'max:255',
                new LowercaseUnderscore,
                Rule::unique('contract_attributes')->ignore($this->route('contract_attribute')->id)
            ],
            'name' => [
                'required',
                'max:255',
            ],
        ];
    }
}
