<?php

namespace App\EloquentModels;

class Recipe extends BaseModel
{
    /**
     * Class Recipe
     *
     * @property int $id
     * @property int $api_id
     * @property string $name_en
     * @property string $name_ru
     * @property string $alternate
     * @property int $category_id
     * @property text $instruction_en
     * @property text $instruction_ru
     * @property int $area_id
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photo');
    }

    public function videos()
    {
        return $this->morphOne(Video::class, 'video');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'recipe_tag');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_recipe');
    }

    public function measureProductRecipes() 
    {
        return $this->hasMany(MeasureProductRecipe::class, 'recipe_id', 'id');
    }

    public function getApiId() 
    {
        return $this->api_id;
    }

    public function getNameEn() 
    {
        return $this->name_en;
    }

    public function getNameRu() 
    {
        return $this->name_ru;
    }

    public function getAlternate() 
    {
        return $this->alternate;
    }

    public function getCategoryId() 
    {
        return $this->category_id;
    }

    public function getinstructionen() 
    {
        return $this->instruction_en;
    }

    public function getInstructionRu() 
    {
        return $this->instruction_ru;
    }

    public function getAriaId() 
    {
        return $this->aria_id;
    }

    public function getCreatedAt() 
    {
        return $this->created_at;
    }

    public function getUpdatedAt() 
    {
        return $this->updated_at;
    }
    
    protected static function newFactory()
    {
        return \Database\Factories\RecipeFactory::new();
    }
}
