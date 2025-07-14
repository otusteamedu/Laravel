<?php

namespace App\Http\Resources;

use App\Application\UseCases\News\DTO\NewsDTO;
use App\Http\Resources\Models\NewsApiModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var NewsApiModel $newsDto */
        $newsDto = $this->resource;

        return [
            'id' => $newsDto->id,
            'title' => $newsDto->title,
            'content' => $newsDto->content,
            'is_draft' => $newsDto->is_draft,
            'thumbnail' => $newsDto->thumbnail,
            'created_at' => $newsDto->created_at,
            'updated_at' => $newsDto->updated_at,
            'published_at' => $newsDto->published_at,
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
