<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends BaseModel
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
