<?php

namespace App\Http\Requests\School;

use App\Acl\Acl;
use App\Enum\SslStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreSchoolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_SCHOOL_ADD);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
            ],
            'sub_domain' => [
                'required',
                'string',
                'unique:schools,sub_domain',
            ],
            'ssl_status' => [
                'required',
                'integer',
                new Enum(SslStatus::class),
            ]
        ];
    }
}
