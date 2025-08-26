<?php

declare(strict_types=1);

namespace App\Application\UseCases\News\DTO;

final readonly class NewsDTO
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public bool $isDraft,
        public ?string $thumbnail,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $publishedAt,
        public ?AuthorDTO $author = null,
        public ?CategoryDTO $category = null,
    ) {
    }
}

