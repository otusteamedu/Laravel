<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;

/**
 * Product model.
 *
 * @property int $id Product ID
 * @property string $title Product name
 * @property string|null $description Product description
 * @property int $category_id Category ID for product
 * @property int $brand_id Brand ID for product
 * @property int $stock Quantity of products in stock
 * @property int $price Price of the product
 * @property numeric $rating Rating of the product
 * @property numeric $screen_size Screen size of the product
 * @property int $ram Ram of the product
 * @property int $builtin_memory Builtin memory of the product
 * @property \Illuminate\Support\Carbon $created_at Creation date
 * @property \Illuminate\Support\Carbon $updated_at Last update date
 *
 * @property-read \App\Models\Category $category Product category
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Order> $orders Orders with this product
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\ProductAsset> $assets Assets of this product
 */
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    use Searchable;

    protected $guarded = [];
    protected $table = 'products';

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description ?? null;
    }

    public function getCategoryId(): int
    {
        return $this->category_id;
    }

    public function getStock(): int
    {
        return $this->stock;
    }
    public function getPrice(): int
    {
        return $this->price;
    }

    public function getBrandId(): int
    {
        return $this->brand_id;
    }

    public function getRating(): float
    {
        return $this->rating;
    }

    public function getScreenSize(): float
    {
        return $this->screen_size;
    }

    public function getRam(): int
    {
        return $this->ram;
    }

    public function getBuiltinMemory(): float
    {
        return $this->builtin_memory;
    }

    public function getCreatedAt(): \Illuminate\Support\Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): \Illuminate\Support\Carbon
    {
        return $this->updated_at;
    }

    public function getCategory(): \App\Models\Category
    {
        return $this->category;
    }

    public function getOrders(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->orders;
    }

    public function getAssets(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->assets;
    }

    public function getFirstImage(): ?\App\Models\ProductAsset   
    {
        return $this->first_image;
    }

    public function getBrand(): \App\Models\Brand
    {
        return $this->brand;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_products', 'product_id', 'order_id');
    }

    public function assets(): HasMany
    {
        return $this->hasMany(ProductAsset::class);
    }

    public function first_image(): HasOne
    {
        return $this->hasOne(ProductAsset::class)->where('type', 'image')->orderBy('id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'rating' => $this->rating,
            'screen_size' => $this->screen_size,
            'ram' => $this->ram,
            'builtin_memory' => $this->builtin_memory
        ];
    }
}