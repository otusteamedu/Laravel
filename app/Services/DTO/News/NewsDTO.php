<?php

namespace App\Services\DTO\News;

use Carbon\Carbon;

final readonly class NewsDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public bool $isDraft,
        public ?string $thumbnail,
        public ?Carbon $createdAt,
        public ?Carbon $publishedAt,
        public ?Carbon $updatedAt = null,
        public ?AuthorDTO $author = null,
        public ?CategoryDTO $category = null,
    ) {
    }
}
