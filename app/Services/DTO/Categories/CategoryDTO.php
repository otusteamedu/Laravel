<?php

namespace App\Services\DTO\Categories;

final readonly class CategoryDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $color,
        public ?string $description,
        public int $tasks_count,
    ) {
    }
} 