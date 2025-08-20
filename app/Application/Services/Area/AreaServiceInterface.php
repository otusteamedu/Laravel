<?php

namespace App\Application\Services\Area;

interface AreaServiceInterface 
{
    /**
     * @return array <int, AreaDTO>
     */
    public function prepairDataForIndex(): array;

    public function store(string $name, string $lang): void;

    public function prepairDataForEdit(int $id): AreaDTO;
    
    public function update(int $id, string $name): void;

    public function delete(int $id): void;
}
