<?php

namespace App\Http\Requests\User;

use App\Acl\Acl;
use App\Enum\UserStatus;
use App\Rules\AlphaSpaces;
use App\Rules\CheckEmojiRule;
use App\Rules\PhoneNumber;
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
        $rules = [
            'first_name' => ['required', 'string', 'max:30', new AlphaSpaces],
            'last_name' => ['required', 'string', 'max:30', new AlphaSpaces],
            'roles' => 'sometimes',
            'phone_number' => ['required', new PhoneNumber],
            'user_avatar' => ['nullable'],
            'notification_email' => ['nullable'],
            'identification_number' => ['required', 'numeric', 'digits_between:1,14'],
            'bank_name' => [
                'required',
                'string',
                'max:255',
                new AlphaSpaces
            ],
            'bank_number' => ['required', 'numeric', 'digits_between:1,14'],
            'relative_name' => [
                'required',
                'string',
                'max:255',
            ],
            'relative_number' => ['required', new PhoneNumber, 'different:phone_number'],
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
            'departments' => ['required'],
            'titles' => ['required'],
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
            $rules['personal_income_tax'] = 'numeric|digits_between:1,12';
        }

        if ($this->insurance_number) {
            $rules['insurance_number'] = 'string|max:30';
        }

        return $rules;
    }
}
