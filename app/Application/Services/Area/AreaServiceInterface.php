<?php

namespace App\Application\Services\Area;

interface AreaServiceInterface 
{
    /**
     * @return array <int, AreaDTO>
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    public function prepairDataForIndex(): array;

    /**
     * @throws ServiceException
     */
    public function store(string $name, string $lang): void;

    /**
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    public function prepairDataForEdit(int $id): AreaDTO;
    
    /**
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    public function update(int $id, string $name): void;

    /**
     * @throws NotFoundServiceException
     * @throws ServiceException
     */
    public function delete(int $id): void;
}
