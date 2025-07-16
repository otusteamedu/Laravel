<?php

namespace App\EloquentModels;

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

    public function getRecipeId() 
    {
        return $this->recipe_id;
    }

    public function getProductId() 
    {
        return $this->product_id;
    }

    public function getMeasureId() 
    {
        return $this->measure_id;
    }

    public function getValue() 
    {
        return $this->value;
    }

    public function getCreatedAt() 
    {
        return $this->created_at;
    }

    public function getUpdatedAt() 
    {
        return $this->updated_at;
    }
}
