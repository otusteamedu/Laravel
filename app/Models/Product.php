<?php

namespace App\Models;

use App\Events\ProductPriceChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @class Product
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string|null $text
 * @property string|null $image
 * @property string|null $images // Stored as JSON string in DB, accessed as array
 * @property int $is_sale // Stored as tinyInteger, accessed as bool
 * @property int $published // Stored as tinyInteger, accessed as bool
 * @property int $order
 * @property float $price
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Product extends BaseModel
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * Temporary storage for price change data
     *
     * @var array|null
     */
    private $priceChangeData = null;


    protected $fillable = [
        'title',
        'alias',
        'text',
        'image',
        'images',
        'is_sale',
        'published',
        'order',
        'price',
    ];


    public function categories(){
        return $this->belongsToMany(Category::class);
    }

}
