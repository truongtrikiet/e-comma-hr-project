<?php

namespace App\Http\Requests\Furlough;

use App\Acl\Acl;
use App\Enum\FurloughStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ApprovedFurloughRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_FURLOUGH_SHOW);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'furlough_status' => [
                'required',
                new Enum(FurloughStatus::class),
            ]
        ];
    }
}
