<?php

namespace App\Infrastructure\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @class CartItem
 *
 * @property int $id
 * @property int $cart_id
 * @property int $product_id
 * @property float $price
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Infrastructure\Eloquent\Models\Cart $cart
 * @property-read \App\Infrastructure\Eloquent\Models\Product $product
 */
class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'price',
        'quantity'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    /**
     * Получить корзину элемента
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Получить товар элемента
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Получить общую стоимость элемента
     */
    public function getTotalAttribute()
    {
        return $this->quantity * $this->product->price;
    }

    /**
     * Обновить цену из продукта
     */
    public function updatePriceFromProduct(): void
    {
        if ($this->product) {
            $this->price = $this->product->price;
            $this->save();
        }
    }
}
