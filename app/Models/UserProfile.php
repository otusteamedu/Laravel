<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $biography
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 */
class UserProfile extends BaseModel
{
    /** @use HasFactory<\Database\Factories\UserProfileFactory> */
    use HasFactory;

    protected $table = 'user_profiles';

    public $fillable = ['user_id', 'biography'];

    /**
     * Пользоваетль профиля
     * @return BelongsTo<User, UserProfile>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
