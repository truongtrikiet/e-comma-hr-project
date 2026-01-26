<?php

namespace App\Http\Requests\Department;

use App\Acl\Acl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enum\DepartmentType;
use App\Enum\SettingStatus;

class UpdateDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
    return checkPermission(Acl::PERMISSION_DEPARTMENT_EDIT);
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
            'description' => [
                'nullable',
                'string',
            ],
            'type' => [
                'integer',
                'required',
                new Enum(DepartmentType::class),
            ],
            'status' => [
                'integer',
                'required',
                new Enum(SettingStatus::class),
            ],
        ];
    }
}
