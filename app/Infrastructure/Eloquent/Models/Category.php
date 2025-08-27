<?php

namespace App\Infrastructure\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\CategoryFactory;

/**
 * @class Category
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string|null $text
 * @property int $published // Stored as tinyInteger, accessed as bool
 * @property int $order
 * @property int $user_id
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
        return $this->belongsToMany(Product::class);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CategoryFactory::new();
    }
}
