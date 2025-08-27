<?php

namespace App\Infrastructure\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @class Cart
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $guest_token
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Infrastructure\Eloquent\Models\CartItem[] $items
 * @property-read \App\Infrastructure\Eloquent\Models\User|null $user
 */
class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'guest_token', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    /**
     * Получить пользователя корзины
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить товары в корзине
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Получить общую стоимость корзины
     */
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    /**
     * Получить общее количество товаров
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    /**
     * Scope для активных корзин
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Найти корзину по guest_token
     */
    public static function findByToken($token)
    {
        return static::active()->where('guest_token', $token)->first();
    }

    /**
     * Создать корзину для гостя
     */
    public static function createForGuest()
    {
        return static::create([
            'guest_token' => Str::random(32),
            'expires_at' => now()->addDays(30)
        ]);
    }

    /**
     * Проверить, является ли корзина гостевой
     */
    public function isGuestCart()
    {
        return !is_null($this->guest_token);
    }

    /**
     * Обновить время жизни корзины
     */
    public function refreshExpiry()
    {
        $this->update(['expires_at' => now()->addDays(30)]);
    }

    /**
     * Привязать корзину к пользователю
     */
    public function assignToUser($userId)
    {
        $this->update([
            'user_id' => $userId,
            'guest_token' => null,
            'expires_at' => null
        ]);
    }

    /**
     * Объединить корзины (при логине пользователя)
     */
    public function mergeWithUserCart($userId)
    {
        $userCart = static::where('user_id', $userId)->first();

        if ($userCart) {
            // Переносим товары из гостевой корзины в пользовательскую
            foreach ($this->items as $item) {
                $existingItem = $userCart->items()
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($existingItem) {
                    $existingItem->update([
                        'quantity' => $existingItem->quantity + $item->quantity
                    ]);
                    $item->delete();
                } else {
                    $item->update(['cart_id' => $userCart->id]);
                }
            }

            // Удаляем гостевую корзину
            $this->delete();

            return $userCart;
        }

        // Если у пользователя нет корзины, просто привязываем текущую
        $this->assignToUser($userId);
        return $this;
    }

    /**
     * Очистить просроченные корзины
     */
    public static function cleanupExpired()
    {
        static::where('expires_at', '<=', now())->delete();
    }
}
