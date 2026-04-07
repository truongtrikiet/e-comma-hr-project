<?php

namespace App\Http\Requests\Contract;

use App\Enum\ContractStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
                'required',
                'integer',
                'exists:users,id',
            ],
            'contract_type_id' => [
                'required',
                'integer',
                'exists:contract_types,id',
            ],
            'signed_at' => [
                'nullable',
                'date',
            ],
            'expired_at' => [
                'nullable',
                'date',
                'after:signed_at',
            ],
            'status' => [
                'required',
                new Enum(ContractStatus::class),
            ],
            'attributes' => [
                'nullable',
                'array',
            ],
            // key = contract_type_attribute_id
            'attributes.*' => [
                'nullable',
                'string',
            ],
            'appendix_ids' => [
                'nullable',
                'array',
            ],
            'appendix_ids.*' => [
                'integer',
                'exists:appendix_contracts,id',
            ],
        ];
    }
}
