<?php

namespace App\Application\Services\Product;

interface ProductServiceInterface 
{
    /**
     * @return array <int, ProductDTO>
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    // public function prepairDataForIndex(): array;

    /**
     * @throws ServiceException
     */
    public function store(string $name, string $lang): void;

    /**
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    // public function prepairDataForEdit(int $id): ProductDTO;
    
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
