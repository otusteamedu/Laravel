<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Product model.
 * 
 * @property-read int $id Product ID
 * @property-read string $title Product name
 * @property-read string|null $description Product description
 * @property-read int $category_id Category ID for product
 * @property-read int $stock Quantity of products in stock
 * @property-read int $price Price of the product
 * @property-read \Illuminate\Support\Carbon $created_at Creation date
 * @property-read \Illuminate\Support\Carbon $updated_at Last update date
 * 
 * @property-read \App\Models\Category $category Product category
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Order> $orders Orders with this product
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\ProductAsset> $assets Assets of this product
 */
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;
    
    protected $guarded = [];
    protected $table = 'products';

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getTitle(): string
    {
        return $this->attributes['title'];
    }

    public function getDescription(): ?string
    {
        return $this->attributes['description'] ?? null;
    }

    public function getCategoryId(): int
    {
        return $this->attributes['category_id'];
    }

    public function getStock(): int
    {
        return $this->attributes['stock'];
    }
    public function getPrice(): int
    {
        return $this->attributes['price'];
    }

    public function getCreatedAt(): \Illuminate\Support\Carbon
    {
        return $this->attributes['created_at'];
    }

    public function getUpdatedAt(): \Illuminate\Support\Carbon
    {
        return $this->attributes['updated_at'];
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
}
