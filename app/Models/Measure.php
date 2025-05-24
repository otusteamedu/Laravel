<?php

namespace App\Models;

class Measure extends BaseModel
{
    /**
     * Class Measure
     *
     * @property int $id
     * @property string $name_en
     * @property string $name_ru
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function measureProductRecipes() 
    {
        return $this->hasMany(MeasureProductRecipe::class, 'measure_id', 'id');
    }

    public function getId() 
    {
        return $this->id;
    }

    public function getNameEn() 
    {
        return $this->name_en;
    }

    public function getNameRu() 
    {
        return $this->name_ru;
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
