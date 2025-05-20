<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Order model.
 * 
 * @property-read int $id Order ID
 * @property-read int $user_id ID of the user who placed the order
 * @property-read \Illuminate\Support\Carbon $created_at Creation date
 * @property-read \Illuminate\Support\Carbon $updated_at Last update date
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\Product> $products Products in this order
 * @property-read \App\Models\User $user The user who placed this order
 */
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $guarded = [];
    protected $table = 'orders';

    public function getId(): int
    {
        return $this->attributes['id'];
    }

    public function getUserId(): int
    {
        return $this->attributes['user_id'];
    }

    public function getCreatedAt(): \Illuminate\Support\Carbon
    {
        return $this->attributes['created_at'];
    }

    public function getUpdatedAt(): \Illuminate\Support\Carbon
    {
        return $this->attributes['updated_at'];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_id', 'product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
