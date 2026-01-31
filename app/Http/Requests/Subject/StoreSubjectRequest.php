<?php

namespace App\Http\Requests\Subject;

use App\Acl\Acl;
use App\Enum\SettingStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_SUBJECT_ADD);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'school_id' => [
                'required',
                'integer',
                'exists:schools,id'
            ],
            'status' => [
                'required',
                new Enum(SettingStatus::class),
            ]
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
