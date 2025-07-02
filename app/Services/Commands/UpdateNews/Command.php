<?php

namespace App\Services\Commands\UpdateNews;

final readonly class Command
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public ?int $userId,
        public ?int $categoryId,
        public string $publishedAt,
        public bool $isDraft,
    ) {
    }
}
