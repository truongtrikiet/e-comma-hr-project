<?php

namespace App\Http\Requests\CandidateScreening;

use App\Enum\PositionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScanCandidateResumeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ai_profile_id' => [
                'required',
                'integer',
                'exists:ai_profiles,id'
            ],
            'position_type' => [
                'required',
                'string',
                Rule::in(PositionTypeEnum::values()),
            ],
            'files' => [
                'required',
                'array',
                'min:1',
                'max:' . config('ai.batch_size', 10),
            ],
            'files.*' => [
                'file',
                'mimes:pdf,doc,docx',
                'max:5120'
            ],
        ];
    }
}
