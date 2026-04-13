<?php

namespace App\Http\Requests\ContractAttribute;

use App\Acl\Acl;
use App\Rules\LowercaseUnderscore;
use Illuminate\Foundation\Http\FormRequest;

class StoreContractAttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_CONTRACT_ATTRIBUTE_ADD);
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
                'unique:contract_attributes',
            ],
            'name' => [
                'required',
                'max:255',
            ],
        ];
    }
}
