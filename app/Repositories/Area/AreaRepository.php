<?php

namespace App\Repositories\Area;

use App\BusinessModels\Area as BusinessModelsArea;
use App\EloquentModels\Area;

class AreaRepository implements AreaRepositoryInterface
{
    /**
     * @return array <int, BusinessModelsArea>
     */
    public function getAll(): array
    {
        $areas = Area::all()->sortBy('id');
        $areas = $areas->map(function($area) {
            return (BusinessModelsArea::createFromEloquentModel($area));
        });
        return $areas->toArray();
    }

    public function store(BusinessModelsArea $area): void
    {
        Area::create($area->toArray());
    }

    public function findById(int $id): BusinessModelsArea
    {
        $area = Area::findOrFail($id);
        return BusinessModelsArea::createFromEloquentModel($area);
    }

    public function update(BusinessModelsArea $area): void
    {
        $areaEloquent = Area::findOrFail($area->id);
        $areaEloquent->update($area->toArray());
    }

    public function delete(int $id): void
    {
        Area::destroy($id);
    }
}
