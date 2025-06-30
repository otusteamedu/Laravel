<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Order model.
 *
 * @property int $id Order ID
 * @property int $user_id ID of the user who placed the order
 * @property int $status status of order
 * @property \Illuminate\Support\Carbon $created_at Creation date
 * @property \Illuminate\Support\Carbon $updated_at Last update date
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Product> $products Products in this order
 * @property-read \App\Models\User $user The user who placed this order
 */
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    const ORDER_STATUS_NAMES = [
        1 => 'Новый',
        2 => 'Ожидается подтверждение оплаты',
        3 => 'Оплачен',
        4 => 'Неудачная оплата',
        5 => 'Выдан',
    ];

    protected $guarded = [];
    protected $table = 'orders';

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getStatus(): int
    {
        return $this->status;
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

    public function getUser(): \App\Models\User
    {
        return $this->user;
    }

    public function getTotal(): int
    {
        $products = $this->products;
        $total = 0;
        foreach ($products as $product) {
            $total += $product->pivot->paid_price * $product->pivot->count;
        }
        return $total;
    }

    public function getStatusName(): string{
        return self::ORDER_STATUS_NAMES[$this->status];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id')->withPivot(['count', 'paid_price']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
