<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\BusinessModels\MeasureProductRecipe as BusinessModelMeasureProductRecipe;
use App\Domain\BusinessModels\BaseModel as BusinessBaseModel;
use App\Domain\BusinessModels\Measure as BusinessModelMeasure;
use App\Domain\BusinessModels\Product as BusinessModelProduct;
use App\Domain\BusinessModels\Recipe as BusinessModelRecipe;
use App\Domain\ValueObjects\MeasureProductRecipe\MeasureProductRecipeValue;
use Carbon\Carbon;

class MeasureProductRecipe extends BaseModel
{
    protected $table = 'measure_product_recipe';

    /**
     * Class MeasureProductRecipe
     *
     * @property int $id
     * @property int $product_id
     * @property int $recipe_id
     * @property int $measure_id
     * @property string $value
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function measure()
    {
        return $this->belongsTo(Measure::class, 'measure_id', 'id');
    }

    public function getRecipe(): BusinessModelRecipe
    {
        $recipeEloquent = $this->recipe;
        $recipe = $recipeEloquent->toBusinessModel();
        return $recipe;
    }

    public function getProduct(): BusinessModelProduct
    {
        $productEloquent = $this->product;
        $product = $productEloquent->toBusinessModel();
        return $product;
    }

    public function getMeasure(): BusinessModelMeasure
    {
        $measureEloquent = $this->measure;
        $measure = $measureEloquent->toBusinessModel();
        return $measure;
    }

    public function getValue(): MeasureProductRecipeValue
    {
        return new MeasureProductRecipeValue($this->value);
    }

    public function getCreatedAt(): string
    {
        $data = Carbon::createFromDate($this->created_at)->format('d.m.Y');
        return $data;
    }

    public function getUpdatedAt(): string
    {
        $data = Carbon::createFromDate($this->updated_at)->format('d.m.Y');
        return $data;
    }

    public function toBusinessModel(): ?BusinessBaseModel
    {
        return new BusinessModelMeasureProductRecipe(
            recipe: $this->getRecipe(),
            product: $this->getProduct(),
            measure: $this->getMeasure(),
            value: $this->getValue(),
            id: $this->getId(),
            created_at: $this->getCreatedAt()
        );
    }

    protected static function newFactory()
    {
        return \Database\Factories\MeasureProductRecipeFactory::new();
    }
}
