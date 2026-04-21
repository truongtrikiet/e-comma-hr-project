<?php

namespace App\Http\Resources\AI;

use App\Enum\CandidateScreeningStatus;
use App\Enum\PositionTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidateScreeningResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'school' => $this->whenLoaded('school'),
            'ai_profile' => $this->whenLoaded('aiProfile'),
            'candidate_name' => $this->candidate_name ?? 'N/A',
            'candidate_email' => $this->candidate_email ?? 'N/A',
            'candidate_phone_number' => $this->candidate_phone_number ?? 'N/A',
            'position_type' => $this->position_type?->value,
            'position_type_name' => PositionTypeEnum::getNameByValue($this->position_type->value),
            'status' => $this->status,
            'status_name' => CandidateScreeningStatus::getNameByValue($this->status->value),
            'status_badge' => $this->status?->getBadge(),
            'emailed_at' => $this->emailed_at?->format('d/m/Y H:i:s') ?? 'N/A',
            'screened_at' => $this->screened_at?->format('d/m/Y H:i:s') ?? 'N/A',
            'created_at' => $this->created_at?->format('d/m/Y H:i:s') ?? 'N/A',
            'updated_at' => $this->updated_at?->format('d/m/Y H:i:s') ?? 'N/A',
        ];
    }
}
