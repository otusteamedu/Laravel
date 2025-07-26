<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource;
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at->format('d.m.Y H:i'),
            'role_id' => $user->role_id,
            'role_title' => $user->role->title,
            'created_at' => $user->created_at->format('d.m.Y H:i'),
            'updated_at' => $user->updated_at->format('d.m.Y H:i'),
        ];
    }
}
