<?php

namespace App\Http\Resources;

use App\Application\UseCases\News\DTO\NewsDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsDTOResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var NewsDTO $newsDto */
        $newsDto = $this->resource;

        return [
            'id' => $newsDto->id,
            'title' => $newsDto->title,
            'content' => $newsDto->content,
            'is_draft' => $newsDto->isDraft,
            'thumbnail' => $newsDto->thumbnail,
            'created_at' => $newsDto->createdAt?->format('Y-m-d H:i:s'),
            'updated_at' => $newsDto->updatedAt?->format('Y-m-d H:i:s'),
            'published_at' => $newsDto->publishedAt?->format('Y-m-d H:i:s'),
            'author' => $newsDto->author ? [
                'id' => $newsDto->author->id,
                'name' => $newsDto->author->name,
                'email' => $newsDto->author->email,
            ] : null,
            'category' => $newsDto->category ? [
                'id' => $newsDto->category->id,
                'name' => $newsDto->category->name,
                'slug' => $newsDto->category->slug,
            ] : null,
        ];
    }
}
