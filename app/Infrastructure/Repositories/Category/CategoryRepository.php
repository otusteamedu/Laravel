<?php

namespace App\Infrastructure\Repositories\Category;

use App\Domain\BusinessModels\Category as BusinessModelsCategory;
use App\Infrastructure\EloquentModels\Category;
use App\Application\Services\Category\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @return array <int, BusinessModelsCategory>
     */
    // public function getAll(): array
    // {
    //     $models = Category::all()
    //         ->sortBy('id')
    //         ->map(fn($model) => $model->toBusinessModel())
    //         ->filter()
    //         ->values()
    //         ->all();
    //     return $models;
    // }

    public function store(BusinessModelsCategory $model): void
    {
        Category::create($this->toArrayForEloquent($model));
    }

    // public function findById(int $id): BusinessModelsCategory
    // {
    //     $model = Category::findOrFail($id);
    //     return $model->toBusinessModel();
    // }

    // public function update(
    //     BusinessModelsCategory $model,
    //     ?string $lang = null
    // ): void {
    //     $modelEloquent = Category::findOrFail($model->getId());
    //     $modelEloquent->update($this->toArrayForEloquent($model, $lang));
    // }

    // public function delete(int $id): void
    // {
    //     $model = Category::findOrFail($id);
    //     $model->delete();
    // }

    /**
     * @return array <int, int $model_id>
     */
    // public function getIdWhereNullField(string $nameField): array
    // {
    //     $models = Category::whereNull($nameField)
    //         ->pluck('id')
    //         ->toArray();
    //     return $models;
    // }

    /**
     * @return array <string $lang, string $value, string $created_at>
     */
    // public function findPresenceLangById(int $id): array
    // {
    //     $model = Category::findOrFail($id);
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
        return Category::pluck($field)->toArray();
    }

    private function toArrayForEloquent(BusinessModelsCategory $model, ?string $lang = null): array
    {
        $array = [
            'name_' . ($lang ?? $model->getLang()->getValue()) => $model->getName()->getValue(),
            'description_' . ($lang ?? $model->getLang()->getValue()) => $model->getName()->getValue(),
            'api_id' => $model->getApiId(),
        ];
        return $array;
    }
}
