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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'status_name' => __(Str::title($this->status->name)),
            'badge_name' => $this->status?->getBadge(),
            'login_at' => $this->login_at?->format('d/m/Y H:i:s'),
            'created_at' => $this->created_at?->format('d/m/Y H:i:s'),
            'updated_at' => $this->updated_at?->format('d/m/Y H:i:s'),
            'school' => $this->whenLoaded('school'),
            'role' => $this->whenLoaded('roles'),
        ];
    }
}
