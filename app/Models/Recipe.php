<?php

namespace App\Models;

class Recipe extends BaseModel
{
    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function photo()
    {
        return $this->morphMany(Photo::class, 'photo');
    }

    public function video()
    {
        return $this->morphOne(Video::class, 'video');
    }

    public function tag()
    {
        return $this->belongsToMany(Tag::class, 'recipe_tag');
    }

    public function product()
    {
        return $this->belongsToMany(Product::class, 'product_recipe');
    }

    public function measureProductRecipe() 
    {
        return $this->hasMany(MeasureProductRecipe::class, 'recipe_id', 'id');
    }
}
