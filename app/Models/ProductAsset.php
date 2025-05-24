<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Product asset model.
 *
 * @property int $id Asset ID
 * @property int $product_id Asset product ID
 * @property string $asset_url Asset URL
 * @property 'image'|'video' $type Type of asset
 * @property \Illuminate\Support\Carbon $created_at Creation date
 * @property \Illuminate\Support\Carbon $updated_at Last update date
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
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getAssetUrl(): string
    {
        return $this->asset_url;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCreatedAt(): \Illuminate\Support\Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): \Illuminate\Support\Carbon
    {
        return $this->updated_at;
    }

    public function getProduct(): \App\Models\Product
    {
        return $this->product;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
