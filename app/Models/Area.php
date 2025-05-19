<?php

namespace App\Models;

class Area extends BaseModel
{
    public function recipe()
    {
        return $this->hasMany(Recipe::class, 'area_id', 'id');
    }
}
