<?php

namespace App\Application\Services\MeasureProductRecipe;

use App\Domain\BusinessModels\MeasureProductRecipe;

interface MeasureProductRecipeRepositoryInterface
{
    /**
     * @return array <int, MeasureProductRecipe>
     */
    // public function getAll(): array;

    public function store(MeasureProductRecipe $category): void;

    // public function findById(int $id): MeasureProductRecipe;

    // public function update(MeasureProductRecipe $category, ?string $lang = null): void;

    // public function delete(int $id): void;

    /**
     * @return array <int, mixed $value>
     */
    public function getValueByField(string $field): array;
}
