<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends BaseModel
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    public function categories(){
        return $this->belongsToMany(Category::class);
    }
}
