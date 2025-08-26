<?php

namespace App\Infrastructure\Repositories\Area;

use App\Domain\BusinessModels\Area as BusinessModelsArea;
use App\Infrastructure\EloquentModels\Area;
use App\Application\Services\Area\AreaRepositoryInterface;
use App\Domain\ValueObjects\Lang;

class AreaRepository implements AreaRepositoryInterface
{
    /**
     * @return array <int, BusinessModelsArea>
     */
    public function getAll(): array
    {
        $models = Area::all()
            ->sortBy('id')
            ->map(fn($model) => $model->toBusinessModel())
            ->filter()
            ->values()
            ->all();
        return $models;
    }

    public function store(BusinessModelsArea $model): void
    {
        Area::create($this->toArrayForEloquent($model));
    }

    public function findById(int $id): BusinessModelsArea
    {
        $model = Area::findOrFail($id);
        return $model->toBusinessModel();
    }
    
    public function findByName(string $name, Lang $lang): BusinessModelsArea
    {
        $model = Area::where('name_' . $lang->getValue(), $name);
        return $model->toBusinessModel();
    }

    public function update(
        BusinessModelsArea $model,
        ?string $lang = null
    ): void {
        $modelEloquent = Area::findOrFail($model->getId());
        $modelEloquent->update($this->toArrayForEloquent($model, $lang));
    }

    public function delete(int $id): void
    {
        $model = Area::findOrFail($id);
        $model->delete();
    }

    /**
     * @return array <int, int $model_id>
     */
    public function getIdWhereNullField(string $nameField): array
    {
        $models = Area::whereNull($nameField)
            ->pluck('id')
            ->toArray();
        return $models;
    }

    /**
     * @return array <string $lang, string $value, string $created_at>
     */
    public function findPresenceLangById(int $id): array
    {
        $model = Area::findOrFail($id);
        if (!is_null($model->name_en)) {
            $result = [
                'lang' => 'en',
                'value' => $model->name_en,
                'created_at' => $model->created_at
            ];
        }
        if (!is_null($model->name_ru)) {
            $result = [
                'lang' => 'ru',
                'value' => $model->name_ru,
                'created_at' => $model->created_at
            ];
        }
        return $result;
    }

    private function toArrayForEloquent(BusinessModelsArea $model, ?string $lang = null): array
    {
        $array = [
            'name_' . ($lang ?? $model->getLang()->getValue()) => $model->getName()->getValue(),
        ];
        return $array;
    }

    /**
     * @return array <int, mixed $value>
     */
    public function getValueByField(string $field): array
    {
        return Area::pluck($field, 'id')->toArray();
    }
}
