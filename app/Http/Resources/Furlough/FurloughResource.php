<?php

namespace App\Http\Resources\Furlough;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use App\Enum\DurationType;
use App\Enum\HalfDaySession;

class FurloughResource extends JsonResource
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
            'user_name' => $this->whenLoaded('user', $this->user->name),
            'email' => $this->whenLoaded('user', $this->user->email),
            'user_id' => $this->whenLoaded('user', $this->user->id),
            'school' => $this->whenLoaded('school', $this->school->name),
            'furlough_type_name' => $this->whenLoaded('furloughType', $this->furloughType->name),
            'reason' => $this->reason,
            'duration_type' => $this->duration_type,
            'duration_type_name' => $this->duration_type instanceof \BackedEnum ? DurationType::getNameByValue((string) $this->duration_type->value) : __(Str::title((string) $this->duration_type)),
            'half_day_session' => $this->half_day_session,
            'half_day_session_name' => $this->half_day_session instanceof \BackedEnum ? HalfDaySession::getNameByValue((string) $this->half_day_session->value) : ($this->half_day_session ? __(Str::title((string) $this->half_day_session)) : null),
            'start_time' => customDateFormat($this->start_time),
            'end_time' => customDateFormat($this->end_time),
            'furlough_status' => $this->furlough_status,
            'furlough_status_name' => __(Str::title($this->furlough_status->name)),
            'furlough_status_badge_name' => $this->furlough_status->getBadge(),
        ];
    }
}
