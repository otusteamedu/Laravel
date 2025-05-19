<?php

namespace App\Models;

class MeasureProductRecipe extends BaseModel
{
    protected $table = 'measure_product_recipe';

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
}
