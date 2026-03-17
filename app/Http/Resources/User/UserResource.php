<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $profile = $this->userProfile;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'status' => $this->status,
            'status_name' => __(Str::title($this->status->name)),
            'badge_name' => $this->status?->getBadge(),
            'login_at' => $this->login_at?->format('d/m/Y | H:i:s'),
            'created_at' => $this->created_at?->format('d/m/Y | H:i:s'),
            'updated_at' => $this->updated_at?->format('d/m/Y | H:i:s'),
            'school' => $this->whenLoaded('school'),
            'role' => $this->whenLoaded('roles'),
            'employee_code' => $profile->employee_code ?? 'N/A',
            'date_of_birth' => $profile?->date_of_birth?->format('d/m/Y'),
            'gender' => $profile?->gender?->value,
            'gender_name' => $profile?->gender?->name,
            'position' => $profile?->position,
            'entry_date' => $profile?->entry_date?->format('d/m/Y'),
            'employment_status' => $profile?->employment_status?->value,
            'employment_status_name' => __(Str::title($profile?->employment_status?->name)),
            'subject_id' => $profile?->subject_id,
            'employee_type' => $profile?->employee_type?->value,
        ];
    }
}
