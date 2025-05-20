<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Product asset model.
 * 
 * @property-read int $id Asset ID
 * @property-read int $product_id Asset product ID
 * @property-read string $asset_url Asset URL
 * @property 'image'|'video' $type Type of asset
 * @property-read \Illuminate\Support\Carbon $created_at Creation date
 * @property-read \Illuminate\Support\Carbon $updated_at Last update date
 * 
 * @property-read \App\Models\Product $product Asset product
 */
class ProductAsset extends Model
{
    /** @use HasFactory<\Database\Factories\ProductAssetFactory> */
    use HasFactory;

    protected $guarded = [];
    protected $table = 'product_assets';

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getProductId(): int
    {
        return $this->attributes['product_id'];
    }

    public function getAssetUrl(): string
    {
        return $this->attributes['asset_url'];
    }

    public function getType(): string
    {
        return $this->attributes['type'];
    }

    public function getCreatedAt(): \Illuminate\Support\Carbon
    {
        return $this->attributes['created_at'];
    }

    public function getUpdatedAt(): \Illuminate\Support\Carbon
    {
        return $this->attributes['updated_at'];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
