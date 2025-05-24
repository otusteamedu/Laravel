<?php

namespace App\Models;

class Area extends BaseModel
{
    /**
     * Class Area
     *
     * @property int $id
     * @property string $name_en
     * @property string $name_ru
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */
    
    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'area_id', 'id');
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
