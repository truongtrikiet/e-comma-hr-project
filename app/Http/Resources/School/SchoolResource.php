<?php

namespace App\Http\Resources\School;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SchoolResource extends JsonResource
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
            'sub_domain' => $this->sub_domain,
            'ssl_status' => $this->ssl_status,
            'ssl_status_name' => __(Str::title($this->ssl_status->name)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
