<?php

namespace App\Http\Resources\FurloughPolicy;

use App\Enum\ActiveStatus;
use App\Enum\ResetTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enum\IsPaid;
use App\Enum\MonthEnum;

class FurloughPolicyResource extends JsonResource
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
            'employee_type' => $this->whenLoaded('employeeType'),
            'employee_type_name' => $this->whenLoaded('employeeType', $this->employeeType->name),
            'furlough_type' => $this->whenLoaded('furloughType'),
            'furlough_type_name' => $this->whenLoaded('furloughType', $this->furloughType->name),
            'accrual_per_month' => $this->accrual_per_month,
            'max_days' => $this->max_days,
            'carry_forward' => $this->carry_forward,
            'carry_forward_name' => $this->carry_forward ? __('general.common.yes') : __('general.common.no'),
            'is_paid' => $this->is_paid,
            'is_paid_name' => IsPaid::getNameByValue($this->is_paid?->value) ?? 'N/A',
            'is_paid_badge' => $this->is_paid?->getBadge(),
            'reset_type' => $this->reset_type,
            'reset_type_name' => ResetTypeEnum::getNameByValue($this->reset_type?->value) ?? 'N/A',
            'reset_month' => $this->reset_month,
            'reset_month_name' => MonthEnum::getNameByValue($this->reset_month?->value) ?? 'N/A',
            'status' => $this->status,
            'status_name' => ActiveStatus::getNameByValue($this->status?->value) ?? 'N/A',
            'badge_name' => $this->status?->getBadge(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
