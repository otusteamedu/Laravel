<?php

namespace App\Application\Services\Category;

use App\Domain\BusinessModels\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return array <int, Category>
     */
    // public function getAll(): array;

    public function store(Category $category): void;

    // public function findById(int $id): Category;

    // public function update(Category $category, ?string $lang = null): void;

    // public function delete(int $id): void;

    /**
     * @return array <int, mixed $value>
     */
    public function getValueByField(string $field): array;
}
