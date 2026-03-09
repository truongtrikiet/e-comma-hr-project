<?php

namespace App\Http\Requests\Furlough;

use App\Acl\Acl;
use App\Enum\DurationType;
use App\Enum\FurloughStatus;
use App\Enum\HalfDaySession;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateFurloughRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return checkPermission(Acl::PERMISSION_FURLOUGH_EDIT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'furlough_type_id' => [
                'required',
            ],
            'reason' => [
                'required',
                'string',
                'max:1000',
            ],
            'duration_type' => [
                'required',
                new Enum(DurationType::class),
            ],
            'half_day_session' => [
                'nullable',
                new Enum(HalfDaySession::class),
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
        ];
    }
}
