<?php

namespace App\Http\Resources\School;

use App\Enum\ActiveStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolWorkingCalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $countDays = count($this->working_days);

        return [
            'id' => $this->id,
            'school' => $this->whenLoaded('school', $this->school->name),
            'working_days_count' => $countDays,
            'working_hours_start' => $this->working_hours_start->format('H:i'),
            'working_hours_end' => $this->working_hours_end->format('H:i'),
            'status' => $this->is_active,
            'status_name' => ActiveStatus::getNameByValue($this->is_active->value) ?? 'N/A',
            'badge_name' => $this->is_active?->getBadge(),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
