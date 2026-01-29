<?php

namespace App\Http\Requests\User;

use App\Acl\Acl;
use App\Enum\UserStatus;
use App\Rules\AlphaSpaces;
use App\Rules\CheckEmojiRule;
use App\Rules\PhoneNumber;
use App\Rules\ValidEmail;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_USER_ADD);
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
                'unique:users', 
                new ValidEmail
            ],
            'password' => [
                'nullable',
            ],
            'phone_number' => [
                'nullable', 
                'unique:users,phone_number', 
                new PhoneNumber
            ],
            'user_avatar' => [
                'nullable'
            ],
            'status' => [
                new Enum(UserStatus::class),
            ],
            'roles' => [
                'required',
            ],
            'school_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
