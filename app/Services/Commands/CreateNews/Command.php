<?php

namespace App\Services\Commands\CreateNews;

final readonly class Command
{
    public function __construct(
        public string $title,
        public string $content,
        public ?int $userId,
        public ?int $categoryId,
        public string $publishedAt,
        public bool $isDraft,
    ) {
    }
}
