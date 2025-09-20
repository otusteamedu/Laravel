<?php

namespace App\Application\Services\Category;

interface CategoryServiceInterface 
{
    /**
     * @return array <int, CategoryDTO>
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    // public function prepairDataForIndex(): array;

    /**
     * @throws ServiceException
     */
    public function store(string $name, string $description, string $apiId, string $lang): void;

    /**
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    // public function prepairDataForEdit(int $id): CategoryDTO;
    
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
