<?php

namespace App\Services\DTO\Categories;

final readonly class CategoryDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public bool $isActive,
        public int $sort,
        public ?int $newsCount = null,
    ) {
    }
}
