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
            'name' => $newsDto->name,
            'text' => $newsDto->text,
            'preview' => $newsDto->preview,
            'photo' => $newsDto->photo,
            'created_at' => $newsDto->created_at,
            'updated_at' => $newsDto->updated_at,
            'create_at' => $newsDto->create_at,
            'user_id' => $newsDto->user_id
        ];
    }
}
