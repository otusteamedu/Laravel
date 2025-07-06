<?php

declare(strict_types=1);

namespace App\Application\UseCases\News\DTO;

final readonly class CategoryDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
    ) {
    }
}

