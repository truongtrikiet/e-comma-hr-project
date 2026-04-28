<?php

namespace App\Http\Resources\MeetingSchedule;

use App\Enum\MeetingScheduleStatus;
use App\Enum\MeetingTargetType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingScheduleResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'start_time' => $this->start_time?->format('d/m/Y | H:i:s') ?? 'N/A',
            'end_time' => $this->end_time?->format('d/m/Y | H:i:s') ?? 'N/A',
            'status' => $this->status,
            'status_name' => MeetingScheduleStatus::getNameByValue($this->status->value),
            'status_badge' => $this->status?->getBadge(),
            'created_by' => $this->whenLoaded('user', $this->user?->name ?? 'N/A'),
            'created_at' => $this->created_at?->format('d/m/Y | H:i:s') ?? 'N/A',
            'updated_at' => $this->updated_at?->format('d/m/Y | H:i:s') ?? 'N/A',
            'meeting_schedule_target' => MeetingScheduleTargetResource::collection($this->whenLoaded('targets')),
            'target_type_name' => $this->whenLoaded('targets') && $this->targets->isNotEmpty()
                ? MeetingTargetType::getNameByValue($this->targets->first()->target_type)
                : null,
            'target_id' => $this->whenLoaded('targets') && $this->targets->isNotEmpty()
                ? $this->targets->pluck('target_id')->implode(',')
                : null,
        ];
    }
}
