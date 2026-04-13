<?php

namespace App\Http\Resources\Contract;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code ??'N/A',
            'contract_type_name' => $this->whenLoaded('contractType', htmlspecialchars($this->contractType->name)),
            'contractable_name' => $this->whenLoaded('contractable', $this->contractable?->name ?? null),
            'signed_at' => formatDateDMY($this->signed_at),
            'expired_at' => formatDateDMY($this->expired_at),
            'status_name' => __(Str::title(str_replace('_', ' ', $this->status->name))),
            'status_badge' => $this->status->getBadge(),
        ];
    }
}
