<?php

namespace App\Infrastructure\Repositories\Measure;

use App\Domain\BusinessModels\Measure as BusinessModelsMeasure;
use App\Infrastructure\EloquentModels\Measure;
use App\Application\Services\Measure\MeasureRepositoryInterface;
use App\Domain\ValueObjects\Lang;
use App\Infrastructure\EloquentModels\MeasureProductRecipe;

class MeasureRepository implements MeasureRepositoryInterface
{
    /**
     * @return array <int, BusinessModelsMeasure>
     */
    // public function getAll(): array
    // {
    //     $models = Measure::all()
    //         ->sortBy('id')
    //         ->map(fn($model) => $model->toBusinessModel())
    //         ->filter()
    //         ->values()
    //         ->all();
    //     return $models;
    // }

    public function store(BusinessModelsMeasure $model): void
    {
        Measure::firstOrCreate($this->toArrayForEloquent($model));
    }

    public function findById(int $id): BusinessModelsMeasure
    {
        $model = Measure::findOrFail($id);
        return $model->toBusinessModel();
    }

    public function findByName(string $name, Lang $lang): BusinessModelsMeasure
    {
        $model = Measure::where('name_' . $lang->getValue(), $name)->first();
        return $model->toBusinessModel();
    }

    public function update(
        BusinessModelsMeasure $model,
        ?string $lang = null
    ): void {
        $modelEloquent = Measure::findOrFail($model->getId());
        $modelEloquent->update($this->toArrayForEloquent($model, $lang));
    }

    // public function delete(int $id): void
    // {
    //     $model = Measure::findOrFail($id);
    //     $model->delete();
    // }

    /**
     * @return array <int, int $model_id>
     */
    public function getIdWhereNullField(string $nameField): array
    {
        $models = Measure::whereNull($nameField)
            ->pluck('id')
            ->toArray();
        return $models;
    }

    /**
     * @return array <string $lang, string $value, string $created_at>
     */
    public function findPresenceLangById(int $id): array
    {
        $model = Measure::findOrFail($id);
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

    /**
     * @return array <int, mixed $value>
     */
    public function getValueByField(string $field): array
    {
        return Measure::pluck($field, 'id')->toArray();
    }

    public function getMeaureByProductId(int $productId, Lang $lang): array
    {
        $nameField = 'name_' . $lang->getValue();
        $measure = MeasureProductRecipe::where('product_id', $productId)
            ->with('measure')
            ->get();
        $result = $measure->mapWithKeys(function ($item) use ($nameField) {
            return [$item->measure->id => $item->measure->$nameField];
        })->toArray();
        return $result;
    }

    private function toArrayForEloquent(BusinessModelsMeasure $model, ?string $lang = null): array
    {
        $array = [
            'name_' . ($lang ?? $model->getLang()->getValue()) => $model->getName()->getValue(),
        ];
        return $array;
    }
}
