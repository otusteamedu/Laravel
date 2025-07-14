<?php

declare(strict_types=1);

namespace App\Http\Resources\Mappers;

use App\Application\UseCases\News\DTO\NewsDTO;

use App\Http\Resources\Models\NewsApiModel;
use App\Http\Resources\Models\AuthorApiModel;
use App\Http\Resources\Models\CategoryApiModel;

class NewsApiModelMapper
{
    public static function map(NewsDTO $dto): NewsApiModel
    {
        return new NewsApiModel(
            id: $dto->id,
            title: $dto->title,
            content: $dto->content,
            is_draft: $dto->isDraft,
            thumbnail: $dto->thumbnail,
            created_at: $dto->createdAt?->format('c'),   // ISO 8601
            updated_at: $dto->updatedAt?->format('c'),
            published_at: $dto->publishedAt?->format('c'),
            author: $dto->author ? new AuthorApiModel(
                    id: $dto->author->id,
                    name: $dto->author->name,
                    email: $dto->author->email,
                ) : null,
            category: $dto->category ? new CategoryApiModel(
                    id: $dto->category->id,
                    name: $dto->category->name,
                    slug: $dto->category->slug,
                ) : null,
        );
    }
}
