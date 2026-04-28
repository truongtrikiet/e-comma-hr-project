<?php

namespace App\Http\Resources\MeetingSchedule;

use App\Enum\MeetingTargetType;
use App\Models\User;
use App\Models\Department;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingScheduleTargetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $target = null;
        $typeName = null;

        if ($this->target_type) {
            $typeName = MeetingTargetType::getNameByValue($this->target_type->value ?? $this->target_type);
        }

        switch ($this->target_type?->value ?? $this->target_type) {
            case MeetingTargetType::USER->value:
                $user = User::find($this->target_id);
                $target = $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ] : null;
                break;
            case MeetingTargetType::DEPARTMENT->value:
                $dept = Department::find($this->target_id);
                $target = $dept ? [
                    'id' => $dept->id,
                    'name' => $dept->name,
                ] : null;
                break;
            case MeetingTargetType::SCHOOL->value:
                $school = School::find($this->target_id);
                $target = $school ? [
                    'id' => $school->id,
                    'name' => $school->name,
                ] : null;
                break;
            default:
                $target = null;
        }

        return [
            'id' => $this->id,
            'target_type' => $this->target_type,
            'target_type_name' => $typeName,
            'target_id' => $this->target_id,
            'target' => $target,
        ];
    }
}
