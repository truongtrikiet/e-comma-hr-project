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
        $rules = [
            'first_name' => ['required', 'string', 'max:30', new AlphaSpaces],
            'last_name' => ['required', 'string', 'max:30', new AlphaSpaces],
            'email' => ['required', 'max:100', 'email:rfc,dns', 'unique:users', new ValidEmail],
            'password' => [
                'required',
                'confirmed',
                'max:20',
                Password::min(8)->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'password_confirmation' => ['required'],
            'roles' => 'required',
            'phone_number' => ['required', 'unique:users,phone_number', new PhoneNumber],
            'user_avatar' => ['nullable'],
            'address' => [
                'required',
                'string',
                'max:255',
                new CheckEmojiRule,
            ],
            'birth' => [
                'required',
                'before_or_equal:' . now()->subYears(16)->format('Y-m-d'),
            ],
            'status' => [
                'required',
                new Enum(UserStatus::class),
            ],
            'departments' => ['required',],
        ];

        return $rules;
    }
}
