<?php

namespace App\Application\Services\MeasureProductRecipe;

interface MeasureProductRecipeServiceInterface
{
    /**
     * @return array <int, MeasureProductRecipeDTO>
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    // public function prepairDataForIndex(): array;

    /**
     * @throws ServiceException
     */
    public function store(
        string $recipeApiId,
        string $productName,
        string $measureName,
        string $value,
        ?string $lang = null,
    ): void;

    /**
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    // public function prepairDataForEdit(int $id): MeasureProductRecipeDTO;

    /**
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    // public function update(int $id, string $name): void;

    /**
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    // public function delete(int $id): void;
}
