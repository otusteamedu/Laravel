<?php

namespace App\Services\Area;

use App\Exceptions\NotFoundException;
use App\Repositories\Area\AreaDTO;
use App\Repositories\Area\AreaRepositoryInterface;

final readonly class AreaService implements AreaServiceInterface
{
    public AreaRepositoryInterface $areaRepository;

    public function __construct(AreaRepositoryInterface $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    public function prepairDataForIndex(): array 
    {
        $areas = $this->areaRepository->getAll();  
        if (empty($areas)) {
            throw new NotFoundException('Записи отсутствуют.');
        };
        return $areas;
    }

    public function store(string $name): void 
    {
        $this->areaRepository->store($name);
    }

    public function prepairDataForEdit(int $id): AreaDTO 
    {
        $area = $this->areaRepository->findById($id);
        return $area;
    }
    
    public function update(int $id, string $name): void 
    {
        $areaDTO = $this->areaRepository->findById($id);
        $areaDTO->name = $name;
        $this->areaRepository->update($areaDTO);
    }

    public function delete(int $id): void 
    {
        $area = $this->areaRepository->delete($id);
    }
}
