<?php

namespace App\Repositories\Area;

use App\BusinessModels\Area as BusinessModelsArea;
use App\EloquentModels\Area;
use App\Services\Area\AreaRepositoryInterface;

class AreaRepository implements AreaRepositoryInterface
{
    /**
     * @return array <int, BusinessModelsArea>
     */
    public function getAll(): array
    {
        $areas = Area::all()->sortBy('id');
        $areas = $areas
            ->map(fn($area) => $area->toBusinessModel())
            ->filter()
            ->values();
        return $areas->toArray();
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
