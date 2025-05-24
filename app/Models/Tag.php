<?php

namespace App\Models;

class Tag extends BaseModel
{
    /**
     * Class Tag
     *
     * @property int $id
     * @property string $name_en
     * @property string $name_ru
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_tag');
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
