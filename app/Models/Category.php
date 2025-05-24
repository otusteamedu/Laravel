<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Product category model.
 *
 * @property int $id Category ID
 * @property string $title Category name
 * @property string|null $description Category description
 * @property \Illuminate\Support\Carbon $created_at Creation date
 * @property \Illuminate\Support\Carbon $updated_at Last update date
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Product> $products Products in this category
 */
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    protected $guarded = [];
    protected $table = 'categories';

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

    public function getCreatedAt(): \Illuminate\Support\Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): \Illuminate\Support\Carbon
    {
        return $this->updated_at;
    }

    public function getProducts(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->products;
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
