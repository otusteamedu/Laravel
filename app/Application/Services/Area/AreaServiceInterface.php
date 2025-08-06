<?php

namespace App\Application\Services\Area;

interface AreaServiceInterface 
{
    /**
     * @return array <int, AreaDTO>
     */
    public function prepairDataForIndex(): array;

    /**
     * @return void
     */
    public function store(string $name): void;

    /**
     * @return AreaDTO
     */
    public function prepairDataForEdit(int $id): AreaDTO;
    
    /**
     * @return void
     */
    public function update(int $id, string $name): void;

    /**
     * @return void
     */
    public function delete(int $id): void;
}
