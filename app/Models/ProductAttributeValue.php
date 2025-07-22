<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Database\Factories\ProductAttributeValueFactory;

/**
 * Attribute model.
 *
 * @property int $id
 * @property int $product_id
 * @property int $attribute_id
 * @property string $value
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ProductAttributeValue extends Model
{
    /** @use HasFactory<ProductAttributeValueFactory> */
    use HasFactory;
    protected $table = 'product_attribute_values';

    protected $guarded = [];


    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

    public function getAttributeId(): int
    {
        return $this->attribute_id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }
}
