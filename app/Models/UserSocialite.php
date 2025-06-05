<?php

namespace App\Models;

use App\Models\User;
use App\Models\BaseModel;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $driver
 * @property string $socialite_id
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $deleted_at
 */
class UserSocialite extends BaseModel
{
    protected $table = 'user_socialites';

    public $fillable = ['user_id', 'driver', 'socialite_id'];
}
