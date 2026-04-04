<?php

namespace App\Http\Resources\FurloughPolicyTemplate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enum\ActiveStatus;
use App\Enum\IsPaid;

class FurloughPolicyTemplateResource extends JsonResource
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
            'description' => $this->description,
            'accrual_per_month' => $this->accrual_per_month,
            'max_days' => $this->max_days,
            'carry_forward' => $this->carry_forward,
            'carry_forward_name' => $this->carry_forward ? __('general.common.yes') : __('general.common.no'),
            'carry_forward_badge' => $this->carry_forward ? 'light' : 'dark',
            'is_paid' => $this->is_paid,
            'is_paid_name' => IsPaid::getNameByValue($this->is_paid->value) ?? 'N/A',
            'is_paid_badge' => $this->is_paid?->getBadge(),
            'status' => $this->status,
            'status_name' => ActiveStatus::getNameByValue($this->status->value) ?? 'N/A',
            'badge_name' => $this->status?->getBadge(),
        ];
    }
}
