<?php

namespace App\Repositories\Area;

use App\Models\Area;

class AreaRepository implements AreaRepositoryInterface
{
    public function getAll(): array
    {
        $areas = Area::all()->sortBy('id');
        $areas = $areas->map(function($area) {
            return (new AreaDTO($area));
        });
        return $areas->toArray();
    }

    public function store(string $name): void
    {
        Area::create(['name_' . config('app.locale') => $name]);
    }

    public function findById(int $id): AreaDTO
    {
        $area = Area::findOrFail($id);
        $areaDTO = new AreaDTO($area);
        return $areaDTO;
    }

    public function update(AreaDTO $areaDTO): void
    {
        $area = Area::findOrFail($areaDTO->id);
        $area->update([
            'name_' . config('app.locale') => $areaDTO->name,
        ]);
    }

    public function delete(int $id): void
    {
        Area::destroy($id);
    }
}
