<?php
declare(strict_types=1);

namespace App\Services\News\Commands;

final readonly class CommandDTO
{
    public function __construct(
        public string $title,
        public string $content,
        public ?int $userId,
        public ?int $categoryId,
        public string $publishedAt,
        public bool $isDraft,
        public int $id = 0,
    ) {
    }
}
