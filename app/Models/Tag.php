<?php

namespace App\Models;

class Tag extends BaseModel
{
    public function recipe()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_tag');
    }
}
