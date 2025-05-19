<?php

namespace App\Models;

class Category extends BaseModel
{
    public function recipe() 
    {
        return $this->hasMany(Recipe::class, 'category_id', 'id');
    }
}
