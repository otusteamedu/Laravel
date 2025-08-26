<?php

namespace App\Application\UseCases\News\Commands\UpdateNews;

final readonly class Command
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public ?int $authorId,
        public ?int $categoryId,
        public ?\DateTimeImmutable $publishedAt,
        public bool $isDraft,
    ) {
    }
}
