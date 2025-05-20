<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Product category model.
 * 
 * @property-read int $id Category ID
 * @property-read string $title Category name
 * @property-read string|null $description Category description
 * @property-read \Illuminate\Support\Carbon $created_at Creation date
 * @property-read \Illuminate\Support\Carbon $updated_at Last update date
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

    public function getCreatedAt(): \Illuminate\Support\Carbon
    {
        return $this->attributes['created_at'];
    }

    public function getUpdatedAt(): \Illuminate\Support\Carbon
    {
        return $this->attributes['updated_at'];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
