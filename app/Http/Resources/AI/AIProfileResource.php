<?php

namespace App\Http\Resources\AI;

use App\Enum\ActiveStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AIProfileResource extends JsonResource
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
            'name' => $this->name,
            'school' => $this->whenLoaded('school'),
            'provider' => $this->provider ?? 'N/A',
            'model' => $this->model ?? 'N/A',
            'endpoint' => $this->endpoint,
            'api_key_encrypted' => $this->api_key_encrypted ?? 'N/A',
            'status' => $this->status,
            'status_name' => ActiveStatus::getNameByValue($this->status->value) ?? 'N/A',
            'badge_name' => $this->status?->getBadge(),
        ];
    }
}
