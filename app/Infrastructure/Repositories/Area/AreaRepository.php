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
            ->all();
        return $areas;
    }

    public function store(BusinessModelsArea $area): void
    {
        Area::create($this->toArrayForEloquent($area));
    }

    public function findById(int $id): BusinessModelsArea
    {
        $area = Area::findOrFail($id);
        return $area->toBusinessModel();
    }

    public function update(
        BusinessModelsArea $area,
        ?string $lang = null
    ): void {
        $areaEloquent = Area::findOrFail($area->getId());
        $areaEloquent->update($this->toArrayForEloquent($area, $lang));
    }

    public function delete(int $id): void
    {
        $area = Area::findOrFail($id);
        $area->delete();
    }

    /**
     * @return array <int, int $area_id>
     */
    public function getIdWhereNullField(string $nameField): array
    {
        $areas = Area::whereNull($nameField)
            ->pluck('id')
            ->toArray();
        return $areas;
    }

    /**
     * @return array <string $lang, string $value, string $created_at>
     */
    public function findPresenceLangById(int $id): array
    {
        $area = Area::findOrFail($id);
        if (!is_null($area->name_en)) {
            $result = [
                'lang' => 'en',
                'value' => $area->name_en,
                'created_at' => $area->created_at
            ];
        }
        if (!is_null($area->name_ru)) {
            $result = [
                'lang' => 'ru',
                'value' => $area->name_ru,
                'created_at' => $area->created_at
            ];
        }
        return $result;
    }

    private function toArrayForEloquent(BusinessModelsArea $area, ?string $lang = null): array 
    {
        // if (is_null($area->getId())) {
            $array = [
                'name_' . ($lang ?? $area->getLang()->getValue()) => $area->getName()->getValue(),
            ];
        // } else {
        //     $array = [
        //         'id' => $area->getId(),
        //         'name_' . ($lang ?? $area->getLang()->getValue()) => $area->getName()->getValue(),
        //         'created_at' => $area->getCreatedAt(),
        //     ];
        // }
        return $array;
    }
}
