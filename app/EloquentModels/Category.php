<?php

namespace App\EloquentModels;

class Category extends BaseModel
{
    /**
     * Class Category
     *
     * @property int $id
     * @property string $name_en
     * @property string $name_ru
     * @property text $description_en
     * @property text $description_ru
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function recipes() 
    {
        return $this->hasMany(Recipe::class, 'category_id', 'id');
    }

    public function getNameEn() 
    {
        return $this->name_en;
    }

    public function getNameRu() 
    {
        return $this->name_ru;
    }

    public function getDescriptionEn() 
    {
        return $this->description_en;
    }

    public function getDescriptionRu() 
    {
        return $this->description_ru;
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
        return \Database\Factories\CategoryFactory::new();
    }
}
