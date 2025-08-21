<?php

declare(strict_types=1);

namespace App\Http\Resources\Mappers;

use App\Application\UseCases\News\DTO\NewsDTO;
use App\Http\Resources\Models\NewsApiModel;
use App\Http\Resources\Models\AuthorApiModel;

class NewsApiModelMapper
{
    public static function map(NewsDTO $dto): NewsApiModel
    {
        return new NewsApiModel(
            id: $dto->id,
            name: $dto->name,
            text: $dto->text,
            photo: $dto->photo,
            preview:$dto->preview,
            link:$dto->link,
            created_at: $dto->createdAt?->format('c'),   // ISO 8601
            updated_at: $dto->updatedAt?->format('c'),
            create_at: $dto->createAt?->format('c'),
            user_id: $dto->user_id : null,
        );
    }
}
