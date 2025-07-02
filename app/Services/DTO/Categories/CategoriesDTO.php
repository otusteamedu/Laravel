<?php

namespace App\Services\DTO\Categories;

final readonly class CategoriesDTO
{
    /**
     * @param CategoryDTO[] $results
     */
    public function __construct(
        public array $results
    ) {
    }
}
