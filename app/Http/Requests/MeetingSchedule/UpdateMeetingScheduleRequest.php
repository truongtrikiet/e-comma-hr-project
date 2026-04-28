<?php

namespace App\Http\Requests\MeetingSchedule;

use Illuminate\Foundation\Http\FormRequest;
use App\Acl\Acl;
use App\Enum\MeetingTargetType;
use Illuminate\Validation\Rules\Enum;
use App\Enum\MeetingScheduleStatus;

class UpdateMeetingScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_MEETING_SCHEDULE_EDIT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'sometimes',
                'string',
                'max:255'
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'start_time' => [
                'required',
                'date',
            ],
            'end_time' => [
                'required',
                'date',
                'after:start_time',
            ],
            'status' => [
                'sometimes',
                'integer',
                new Enum(MeetingScheduleStatus::class),
            ],
            'targets' => [
                'sometimes',
                'array',
                'min:1'
            ],
            'targets.*.target_type' => [
                'required_with:targets',
                'integer',
                'in:' . implode(',', array_column(MeetingTargetType::cases(), 'value')),
            ],
            'targets.*.target_ids' => [
                'required_with:targets',
                'array',
                'min:1',
            ],
            'targets.*.target_ids.*' => [
                'integer',
            ],
        ];
    }
}
