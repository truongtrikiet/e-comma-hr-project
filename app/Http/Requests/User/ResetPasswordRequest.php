<?php

namespace App\Http\Requests\User;

use App\Acl\Acl;
use App\Rules\AlphaSpaces;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_USER_EDIT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'confirmed',
                'max:30',
                Password::min(8)->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'old_password' => ['required', 'current_password'],
        ];
    }
}
