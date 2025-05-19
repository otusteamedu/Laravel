<?php

namespace App\Models;

class Measure extends BaseModel
{
    public function measureProductRecipe() 
    {
        return $this->hasMany(MeasureProductRecipe::class, 'measure_id', 'id');
    }
}
