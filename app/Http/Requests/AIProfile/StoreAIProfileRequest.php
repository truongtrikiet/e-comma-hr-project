<?php

namespace App\Http\Requests\AIProfile;

use App\Acl\Acl;
use Illuminate\Foundation\Http\FormRequest;

class StoreAIProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_AI_PROFILE_ADD);
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
            'school_id' => [
                'required',
                'integer',
            ],
            'provider' => [
                'required',
                'string',
            ],
            'model' => [
                'required',
                'string',
            ],
            'endpoint' => [
                'required',
                'string',
            ],
            'api_key_encrypted' => [
                'required',
                'string',
            ],
        ];
    }
}
