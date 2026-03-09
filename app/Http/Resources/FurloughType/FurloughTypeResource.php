<?php

namespace App\Http\Resources\FurloughType;

use App\Enum\ActiveStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FurloughTypeResource extends JsonResource
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
            'status' => $this->status,
            'status_name' => ActiveStatus::getNameByValue($this->status->value) ?? 'N/A',
            'badge_name' => $this->status?->getBadge(),
        ];
    }
}
