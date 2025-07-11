<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $category = $this->resource;
        return [
            'id' => $category->getId(),
            'title' => $category->getTitle(),
            'description' => $category->getDescription(),
            'created_at' => $category->getCreatedAt()->format('d.m.Y H:i'),
        ];
    }
}
