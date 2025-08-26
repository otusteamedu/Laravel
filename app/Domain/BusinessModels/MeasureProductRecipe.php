<?php

namespace App\Domain\BusinessModels;

use App\Domain\Exceptions\NotValidItemDomainException;
use App\Domain\ValueObjects\MeasureProductRecipe\MeasureProductRecipeValue;

class MeasureProductRecipe extends BaseModel implements BusinessModelsInterface
{
    private Recipe $recipe;
    private Product $product;
    private Measure $measure;
    private MeasureProductRecipeValue $value;
    private ?string $created_at;

    public function __construct(
        Recipe $recipe,
        Product $product,
        Measure $measure,
        MeasureProductRecipeValue $value,
        ?int $id = null,
        ?string $created_at = null,
    ) {
        $this->id = $id;
        $this->recipe = $recipe;
        $this->product = $product;
        $this->measure = $measure;
        $this->value = $value;
        $this->created_at = $created_at;
    }

    public function getRecipe(): Recipe
    {
        return $this->recipe;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getMeasure(): Measure
    {
        return $this->measure;
    }

    public function getMeasureProductRecipeValue(): MeasureProductRecipeValue
    {
        return $this->value;
    }

    public function updateValue(MeasureProductRecipeValue $newValue): void
    {
        if ($this->value === $newValue) {
            throw new NotValidItemDomainException("Новое значение совпадает со старым");
        }
        $this->value = $newValue;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function toArray(): array
    {
        $array = [
            'id' => $this->getId(),
            'recipe' => $this->getRecipe()->toArray(),
            'product' => $this->getProduct()->toArray(),
            'measure' => $this->getMeasure()->toArray(),
            'value' => $this->getMeasureProductRecipeValue()->getValue(),
            'created_at' => $this->getCreatedAt(),
        ];

        return $array;
    }
}
