<?php

namespace App\Repositories\Area;

use App\BusinessModels\Area;
use App\Repositories\Area\AreaDTO;

interface AreaRepositoryInterface 
{
    /**
     * @return array <int, Area>
     */
    public function getAll(): array;

    public function store(Area $area): void;

    public function findById(int $id): Area;
    
    public function update(Area $area): void;

    public function delete(int $id): void;
}
