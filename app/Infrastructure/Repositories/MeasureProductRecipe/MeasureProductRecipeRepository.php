<?php

namespace App\Infrastructure\Repositories\MeasureProductRecipe;

use App\Domain\BusinessModels\MeasureProductRecipe as BusinessModelsMeasureProductRecipe;
use App\Infrastructure\EloquentModels\MeasureProductRecipe;
use App\Application\Services\MeasureProductRecipe\MeasureProductRecipeRepositoryInterface;

class MeasureProductRecipeRepository implements MeasureProductRecipeRepositoryInterface
{
    /**
     * @return array <int, BusinessModelsMeasureProductRecipe>
     */
    // public function getAll(): array
    // {
    //     $models = MeasureProductRecipe::all()
    //         ->sortBy('id')
    //         ->map(fn($model) => $model->toBusinessModel())
    //         ->filter()
    //         ->values()
    //         ->all();
    //     return $models;
    // }

    public function store(BusinessModelsMeasureProductRecipe $model): void
    {
        MeasureProductRecipe::firstOrCreate($this->toArrayForEloquent($model));
    }

    // public function findById(int $id): BusinessModelsMeasureProductRecipe
    // {
    //     $model = MeasureProductRecipe::findOrFail($id);
    //     return $model->toBusinessModel();
    // }

    // public function update(
    //     BusinessModelsMeasureProductRecipe $model,
    //     ?string $lang = null
    // ): void {
    //     $modelEloquent = MeasureProductRecipe::findOrFail($model->getId());
    //     $modelEloquent->update($this->toArrayForEloquent($model, $lang));
    // }

    // public function delete(int $id): void
    // {
    //     $model = MeasureProductRecipe::findOrFail($id);
    //     $model->delete();
    // }

    /**
     * @return array <int, int $model_id>
     */
    // public function getIdWhereNullField(string $nameField): array
    // {
    //     $models = MeasureProductRecipe::whereNull($nameField)
    //         ->pluck('id')
    //         ->toArray();
    //     return $models;
    // }

    /**
     * @return array <string $lang, string $value, string $created_at>
     */
    // public function findPresenceLangById(int $id): array
    // {
    //     $model = MeasureProductRecipe::findOrFail($id);
    //     if (!is_null($model->name_en)) {
    //         $result = [
    //             'lang' => 'en',
    //             'value' => $model->name_en,
    //             'created_at' => $model->created_at
    //         ];
    //     }
    //     if (!is_null($model->name_ru)) {
    //         $result = [
    //             'lang' => 'ru',
    //             'value' => $model->name_ru,
    //             'created_at' => $model->created_at
    //         ];
    //     }
    //     return $result;
    // }

    /**
     * @return array <int, mixed $value>
     */
    public function getValueByField(string $field): array 
    {
        return MeasureProductRecipe::pluck($field)->toArray();
    }

    private function toArrayForEloquent(BusinessModelsMeasureProductRecipe $model): array
    {
        $array = [
            'product_id' => $model->getProduct()->getId(),
            'recipe_id' => $model->getRecipe()->getId(),
            'measure_id' => $model->getMeasure()->getId(),
            'value' => $model->getMeasureProductRecipeValue()->getValue(),
        ];
        return $array;
    }
}
