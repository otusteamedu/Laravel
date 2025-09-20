<?php

namespace App\Application\Services\Measure;

interface MeasureServiceInterface 
{
    /**
     * @return array <int, MeasureDTO>
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
    // public function prepairDataForEdit(int $id): MeasureDTO;
    
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
