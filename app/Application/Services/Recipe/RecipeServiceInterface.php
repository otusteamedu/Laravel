<?php

namespace App\Application\Services\Recipe;

interface RecipeServiceInterface
{
    /**
     * @return array <int, RecipeDTO>
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    // public function prepairDataForIndex(): array;

    /**
     * @throws ServiceException
     */
    public function store(
        string $name,
        string $description,
        string $apiId,
        string $lang,
        string $categoryName,
        string $areaName
    ): void;

    /**
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    // public function prepairDataForEdit(int $id): RecipeDTO;

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
