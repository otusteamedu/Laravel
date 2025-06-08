<?php

namespace App\Repositories\Area;

use App\Repositories\Area\AreaDTO;

interface AreaRepositoryInterface 
{
    /**
     * @return array <int, AreaDTO>
     */
    public function getAll(): array;

    /**
     * @return void
     */
    public function store(string $name): void;

    /**
     * @return AreaDTO
     */
    public function findById(int $id): AreaDTO;
    
    /**
     * @return void
     */
    public function update(AreaDTO $area): void;

    /**
     * @return void
     */
    public function delete(int $id): void;
}
