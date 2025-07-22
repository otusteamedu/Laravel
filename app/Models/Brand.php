<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Database\Factories\BrandFactory;

/**
 * Brand model.
 *
 * @property int $id
 * @property string $title
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read Collection<Product> $products
 */
class Brand extends Model
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
