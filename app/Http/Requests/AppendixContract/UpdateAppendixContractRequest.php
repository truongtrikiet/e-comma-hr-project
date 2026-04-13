<?php

namespace App\Http\Requests\AppendixContract;

use App\Acl\Acl;
use App\Enum\AppendixContractStatus;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAppendixContractRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return checkPermission(Acl::PERMISSION_APPENDIX_CONTRACT_EDIT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'content' => [
                'required',
                'string'
            ],
            'status' => [
                'required',
                new Enum(AppendixContractStatus::class),
            ],
        ];
    }
}
