<?php

namespace App\Infrastructure\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @class Order
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $phone
 * @property string $email
 * @property string $status
 * @property float $total_amount
 * @property string|null $shipping_address
 * @property string|null $billing_address
 * @property string|null $customer_note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Infrastructure\Eloquent\Models\OrderItem[] $items
 * @property-read \App\Infrastructure\Eloquent\Models\User $user
 */
class Order extends BaseModel
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'status',
        'total_amount',
        'email',
        'phone',
        'name',
        'shipping_address',
        'billing_address',
        'customer_note'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    /**
     * Получить пользователя заказа
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить товары заказа
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Проверить, является ли заказ ожидающим
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Проверить, можно ли отменить заказ
     */
    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING]);
    }
}
