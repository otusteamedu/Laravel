<?php

namespace App\Infrastructure\Repositories\Recipe;

use App\Domain\BusinessModels\Recipe as BusinessModelsRecipe;
use App\Infrastructure\EloquentModels\Recipe;
use App\Application\Services\Recipe\RecipeRepositoryInterface;

class RecipeRepository implements RecipeRepositoryInterface
{
    /**
     * @return array <int, BusinessModelsRecipe>
     */
    // public function getAll(): array
    // {
    //     $models = Recipe::all()
    //         ->sortBy('id')
    //         ->map(fn($model) => $model->toBusinessModel())
    //         ->filter()
    //         ->values()
    //         ->all();
    //     return $models;
    // }

    public function store(BusinessModelsRecipe $model): void
    {
        Recipe::create($this->toArrayForEloquent($model));
    }

    // public function findById(int $id): BusinessModelsRecipe
    // {
    //     $model = Recipe::findOrFail($id);
    //     return $model->toBusinessModel();
    // }

    // public function update(
    //     BusinessModelsRecipe $model,
    //     ?string $lang = null
    // ): void {
    //     $modelEloquent = Recipe::findOrFail($model->getId());
    //     $modelEloquent->update($this->toArrayForEloquent($model, $lang));
    // }

    // public function delete(int $id): void
    // {
    //     $model = Recipe::findOrFail($id);
    //     $model->delete();
    // }

    /**
     * @return array <int, int $model_id>
     */
    // public function getIdWhereNullField(string $nameField): array
    // {
    //     $models = Recipe::whereNull($nameField)
    //         ->pluck('id')
    //         ->toArray();
    //     return $models;
    // }

    /**
     * @return array <string $lang, string $value, string $created_at>
     */
    // public function findPresenceLangById(int $id): array
    // {
    //     $model = Recipe::findOrFail($id);
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
        return Recipe::pluck($field)->toArray();
    }

    private function toArrayForEloquent(BusinessModelsRecipe $model, ?string $lang = null): array
    {
        $array = [
            'name_' . ($lang ?? $model->getLang()->getValue()) => $model->getName()->getValue(),
            'description_' . ($lang ?? $model->getLang()->getValue()) => $model->getName()->getValue(),
            'api_id' => $model->getApiId(),
        ];
        return $array;
    }
}
