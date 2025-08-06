<?php

namespace App\Infrastructure\Repositories\Area;

use App\Domain\BusinessModels\Area as BusinessModelsArea;
use App\Infrastructure\EloquentModels\Area;
use App\Application\Services\Area\AreaRepositoryInterface;

class AreaRepository implements AreaRepositoryInterface
{
    /**
     * @return array <int, BusinessModelsArea>
     */
    public function getAll(): array
    {
        $areas = Area::all()
                ->sortBy('id')
                ->map(fn($area) => $area->toBusinessModel())
                ->filter()
                ->values()
                ->toArray();
        return $areas;
    }

    public function store(BusinessModelsArea $area): void
    {
        Area::create($area->toArrayForCreat());
    }

    public function findById(int $id): BusinessModelsArea
    {
        $area = Area::findOrFail($id);
        return $area->toBusinessModel();
    }

    public function update(BusinessModelsArea $area): void
    {
        $areaEloquent = Area::findOrFail($area->id);
        $areaEloquent->update($area->toArray());
    }

    public function delete(int $id): void
    {
        $area = Area::findOrFail($id);
        $area->delete();
    }
}
