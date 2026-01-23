<?php

namespace App\Http\Requests\User;

use App\Acl\Acl;
use App\Enum\UserStatus;
use App\Rules\AlphaSpaces;
use App\Rules\PhoneNumber;
use App\Rules\ValidEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
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
            'first_name' => [
                'nullable', 
                'string', 
                'max:30', 
                new AlphaSpaces
            ],
            'last_name' => [
                'nullable', 
                'string', 
                'max:30', 
                new AlphaSpaces
            ],
            'email' => [
                'nullable', 
                'email:rfc,dns', 
                new ValidEmail
            ],
            'password' => [
                'nullable',
            ],
            'phone_number' => [
                'nullable', 
                new PhoneNumber
            ],
            'user_avatar' => [
                'nullable'
            ],
            'status' => [
                new Enum(UserStatus::class),
            ],
        ];
    }
}
