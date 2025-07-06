<?php

namespace App\Application\UseCases\News\Commands\CreateNews;

final readonly class Command
{
    public function __construct(
        public string $title,
        public string $content,
        public ?int $authorId,
        public ?int $categoryId,
        public ?\DateTimeImmutable $publishedAt,
        public bool $isDraft,
    ) {
    }
}
