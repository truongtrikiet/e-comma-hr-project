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
            'id_front' => ['nullable'],
            'id_back' => ['nullable'],
            'notification_email' => ['nullable'],
            'identification_number' => ['required', 'numeric', 'digits_between:1,14', 'unique:user_profiles,identification_number'],
            'bank_name' => [
                'required',
                'string',
                'max:255',
            ],
            'bank_number' => ['required', 'numeric', 'digits_between:1,14', 'unique:user_profiles,bank_number'],
            'relative_name' => [
                'required',
                'string',
                'max:255',
            ],
            'relative_number' => ['required', new PhoneNumber, 'different:phone_number', 'unique:user_profiles,relative_number'],
            'address' => [
                'required',
                'string',
                'max:255',
                new CheckEmojiRule,
            ],
            'birth_place' => [
                'required',
                'string',
                'max:255',
            ],
            'birth' => [
                'required',
                'before_or_equal:' . now()->subYears(16)->format('Y-m-d'),
            ],
            'education_level' => ['required'],
            'relative_role' => ['required'],
            'gender' => ['required'],
            'identification_date' => ['required'],
            'identification_place' => [
                'required',
                'string',
                'max:255',
            ],
            'status' => [
                'required',
                new Enum(UserStatus::class),
            ],
            'departments' => ['required',],
            'titles' => ['required',],
            'employee_type_id' => [
                'required',
                'exists:employee_types,id',
            ],
            'company_entry_date' => [
                'nullable',
                'date',
            ],
            'school_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'field' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];

        if ($this->personal_income_tax) {
            $rules['personal_income_tax'] = [
                'numeric',
                'digits_between:1,12',
                'unique:user_profiles,personal_income_tax',
            ];
        }

        if ($this->insurance_number) {
            $rules['insurance_number'] = 'string|max:30';
        }

        return $rules;
    }
}
