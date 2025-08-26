<?php

namespace App\Application\Services\MeasureProductRecipe;

use App\Domain\BusinessModels\MeasureProductRecipe;

class MeasureProductRecipeDTO 
{
    public ?int $id;
    public ?array $recipe;
    public ?array $product;
    public ?array $measure;
    public ?string $value;
    public ?string $created_at;

    public function __construct(MeasureProductRecipe $model)
    {
        $this->id = $model->getId();
        $this->recipe = $model->getRecipe()->toArray();
        $this->product = $model->getProduct()->toArray();
        $this->measure = $model->getMeasure()->toArray();
        $this->value = $model->getMeasureProductRecipeValue()->getValue();
        $this->created_at = $model->getCreatedAt();
    }

    public function toArray(): array 
    {
        return [
            'id' => $this->id,
            'recipe' => $this->recipe,
            'product' => $this->product,
            'measure' => $this->measure,
            'value' => $this->value,
            'created_at' => $this->created_at
        ];
    }
}
