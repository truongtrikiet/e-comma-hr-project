<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractTypeResource extends JsonResource
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
            'name' => htmlspecialchars($this->name),
            'school' => $this->whenLoaded('school', $this->school->name),
            'contract_attributes' => $this->whenLoaded('contractAttributes'),
            'contracts_count' => $this->contracts_count,
        ];
    }
}
