<?php

namespace App\Http\Resources\CandidateScreening;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Enum\PositionTypeEnum;
use App\Enum\CandidateScreeningStatus;

class CandidateScreeningDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'candidate_name' => $this->candidate_name,
            'candidate_email' => $this->candidate_email,
            'candidate_phone_number' => $this->candidate_phone_number,
            'position_type' => $this->position_type,
            'position_type_name' => PositionTypeEnum::getNameByValue($this->position_type?->value),
            'status' => $this->status,
            'status_name' => CandidateScreeningStatus::getNameByValue($this->status?->value),
            'screened_at' => optional($this->screened_at)->toDateTimeString(),
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
            'resume_file_path' => $this->resume_file_path,
            'resume_url' => $this->resume_file_path ? Storage::url($this->resume_file_path) : null,
            'ai_result_json' => $this->ai_result_json ?? [],
            'recommended_roles' => $this->recommended_roles ?? ($ai['recommended_roles'] ?? []),
            'ai_profile' => $this->aiProfile?->name,
        ];
    }
}
