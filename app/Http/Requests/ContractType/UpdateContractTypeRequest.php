<?php

namespace App\Http\Requests\ContractType;

use Illuminate\Foundation\Http\FormRequest;
use App\Acl\Acl;

class UpdateContractTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_CONTRACT_TYPE_EDIT);
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
                'required',
                'integer',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'content' => [
                'nullable',
                'string',
            ],
            'contract_attribute_ids' => [
                'nullable',
                'array',
            ],
            'contract_attribute_ids.*' => [
                'integer',
                'exists:contract_attributes,id',
            ],
        ];
    }
}
