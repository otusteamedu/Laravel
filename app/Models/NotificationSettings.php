<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @class NotificationSettings
 * @extends Model
 *
 * @property int $id
 * @property int $user_id
 * @property bool $price_changes
 * @property bool $new_products
 * @property bool $sales
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $user
 *
 * This model represents the notification settings for a specific user.
 * It allows users to customize which types of notifications they want to receive.
 */
class NotificationSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'price_changes',
        'new_products',
        'sales',
    ];

    protected $casts = [
        'price_changes' => 'boolean',
        'new_products' => 'boolean',
        'sales' => 'boolean',
    ];

    /**
     * Get the user that owns the notification settings.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
