<?php

namespace App\Infrastructure\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @class OrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $quantity
 * @property float $price
 * @property float $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Infrastructure\Eloquent\Models\Order $order
 * @property-read \App\Infrastructure\Eloquent\Models\Product $product
 */
class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'total'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    /**
     * Получить заказ элемента
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Получить товар элемента
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
