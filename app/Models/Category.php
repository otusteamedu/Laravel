<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @class Category
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string|null $text
 * @property int $published // Stored as tinyInteger, accessed as bool
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */

class Category extends BaseModel
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'alias',
        'text',
        'published'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
