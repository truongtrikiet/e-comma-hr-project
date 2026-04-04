<?php

namespace App\Http\Requests\SchoolWorkingCalendar;

use App\Acl\Acl;
use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolWorkingCalendarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_SCHOOL_WORKING_CALENDAR_ADD);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'school_id' => [
                'required',
                'integer',
                'exists:schools,id'
            ],
            'working_days' => [
                'required',
                'array',
                'min:1'
            ],
            'working_days.*' => [
                'integer',
            ],
            'working_hours_start' => [
                'required',
                'date_format:H:i'
            ],
            'working_hours_end' => [
                'required',
                'date_format:H:i',
                'after:working_hours_start'
            ],
        ];
    }
}
