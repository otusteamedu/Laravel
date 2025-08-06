<?php

namespace App\Application\Services\Area;

use App\Domain\BusinessModels\Area;
use App\Domain\Exceptions\NotFoundException;

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
        $areas = collect($areas)->map(function($area) {
            return (new AreaDTO($area));
        })->toArray();
        return $areas;
    }

    public function store(string $name): void 
    {
        $area = new Area(name:$name);
        $this->areaRepository->store($area);
    }

    public function prepairDataForEdit(int $id): AreaDTO 
    {
        $area = $this->areaRepository->findById($id);
        return new AreaDTO($area);
    }
    
    public function update(int $id, string $name): void 
    {
        $area = $this->areaRepository->findById($id);
        $area->setName($name);
        $this->areaRepository->update($area);
    }

    public function delete(int $id): void 
    {
        $area = $this->areaRepository->delete($id);
    }
}
