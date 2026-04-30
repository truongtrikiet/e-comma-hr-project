<?php

namespace App\Http\Resources\Salary;

use App\Enum\SalaryStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryProposeResource extends JsonResource
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
            'name' => $this->whenLoaded('user', $this->user?->name ?? 'N/A'),
            'email' => $this->user?->email ?? 'N/A',
            'proposed_gross_amount' => customPriceFormatCurrency($this->proposed_gross_amount),
            'proposed_tax_percent' => $this->proposed_tax_percent,
            'proposed_tax_amount' => customPriceFormatCurrency($this->proposed_tax_amount),
            'proposed_net_amount' => customPriceFormatCurrency($this->proposed_net_amount),
            'reason' => $this->reason,
            'effective_date' => $this->effective_date?->format('d/m/Y'),
            'status' => $this->status,
            'status_name' => SalaryStatus::getNameByValue($this->status->value) ?? 'N/A',
            'badge_name' => $this->status?->getBadge(),
            'is_applied' => $this->effective_date ? (bool) SalaryProposeResource::where('user_id', $this->user_id)
                ->whereDate('effective_date', $this->effective_date->toDateString())
                ->where('is_applied', true)
                ->exists() : false,
            'ends_at' => $this->ends_at?->format('d/m/Y'),
            'created_at' => $this->created_at?->format('d/m/Y H:i:s'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i:s'),
        ];
    }
}
