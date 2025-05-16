<?php

namespace App\Models;

use App\Models\User;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $iuser_idd
 * @property string $driver
 * @property string $socialite_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property User[] $users
 */
class UserSocialite extends BaseModel
{
    protected $table = 'user_socialites';

    public $fillable = ['user_id', 'driver', 'socialite_id'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
