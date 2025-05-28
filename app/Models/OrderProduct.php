<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Order-product relationship model.
 *
 * @property int $id Table entry ID
 * @property int $order_id Order ID for relation
 * @property int $product_id Product ID for relation
 * @property int $count Quantity of products
 * @property int $paid_price Price of the product at the time of order creation
 * @property \Illuminate\Support\Carbon $created_at Creation date
 * @property \Illuminate\Support\Carbon $updated_at Last update date
 */
class OrderProduct extends Model
{
    /** @use HasFactory<\Database\Factories\OrderProductFactory> */
    use HasFactory;

    protected $guarded = [];
    protected $table = 'order_products';

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->order_id;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }
    public function getCount(): int
    {
        return $this->count;
    }
    public function getPaidPrice(): int
    {
        return $this->paid_price;
    }

    public function getCreatedAt(): \Illuminate\Support\Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): \Illuminate\Support\Carbon
    {
        return $this->updated_at;
    }
}
