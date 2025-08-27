<?php

namespace App\Application\Services\Recipe;

use App\Domain\BusinessModels\Recipe;

interface RecipeRepositoryInterface
{
    /**
     * @return array <int, Recipe>
     */
    // public function getAll(): array;

    public function store(Recipe $category): void;

    public function findById(int $id): Recipe;

    // public function update(Recipe $category, ?string $lang = null): void;

    // public function delete(int $id): void;

    /**
     * @return array <int, mixed $value>
     */
    public function getValueByField(string $field): array;
}
