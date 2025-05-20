<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Order-product relationship model.
 * 
 * @property-read int $id Table entry ID
 * @property-read int $order_id Order ID for realation
 * @property-read int $product_id Product ID for relation
 * @property-read int $count Quantity of products
 * @property-read int $price Price of the product at the time of order creation
 * @property-read \Illuminate\Support\Carbon $created_at Creation date
 * @property-read \Illuminate\Support\Carbon $updated_at Last update date
 */
class OrderProduct extends Model
{
    /** @use HasFactory<\Database\Factories\OrderProductFactory> */
    use HasFactory;

    protected $guarded = [];
    protected $table = 'order_products';

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getOrderId(): int
    {
        return $this->attributes['order_id'];
    }

    public function getProductId(): int
    {
        return $this->attributes['product_id'];
    }
    public function getCount(): int
    {
        return $this->attributes['count'];
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
}
