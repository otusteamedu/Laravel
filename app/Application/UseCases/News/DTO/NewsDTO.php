<?php

declare(strict_types=1);

namespace App\Application\UseCases\News\DTO;

final readonly class NewsDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $text,
        public string $link,
        public string $preview,
        public string $photo,
        public object $user,
        public ?\DateTimeImmutable $createAt,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
    ) {
    }
}

