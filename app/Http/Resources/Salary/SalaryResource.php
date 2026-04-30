<?php

namespace App\Http\Resources\Salary;

use App\Enum\ExpiredSalaryStatus;
use App\Enum\SalaryStatus;
use App\Models\SalaryPropose;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryResource extends JsonResource
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
            'email' => $this->user->email ?? 'N/A',
            'gross_amount' => customPriceFormatCurrency($this->gross_amount),
            'tax_percent' => $this->tax_percent,
            'tax_amount' => customPriceFormatCurrency($this->tax_amount),
            'net_amount' => customPriceFormatCurrency($this->net_amount),
            'approved_at' => $this->approved_at ? formatDateDMY($this->approved_at) : 'N/A',
            'effective_date' => $this->effective_date ? formatDateDMY($this->effective_date) : 'N/A',
            'ends_at' => $this->ends_at ? formatDateDMY($this->ends_at) : 'N/A',
            'status' => $this->status,
            'status_name' => ExpiredSalaryStatus::getNameByValue($this->status->value) ?? 'N/A',
            'badge_name' => $this->status?->getBadge(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
