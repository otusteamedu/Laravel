<?php

namespace App\Models;

class Product extends BaseModel
{
    public function photo() 
    {
        return $this->morphMany(Photo::class, 'photo');
    }

    public function recipe()
    {
        return $this->belongsToMany(Recipe::class, 'product_recipe');
    }

    public function measureProductRecipe() 
    {
        return $this->hasMany(MeasureProductRecipe::class, 'product_id', 'id');
    }
}
