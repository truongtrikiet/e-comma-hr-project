<?php

namespace App\Http\Requests\FurloughType;

use App\Acl\Acl;
use App\Enum\ActiveStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreFurloughTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_FURLOUGH_TYPE_ADD);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'status' => [
                'required',
                'integer',
                new Enum(ActiveStatus::class),
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
