<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserProfile
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $phone
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property string|null $about
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\UserProfileFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserProfile whereUserId($value)
 *
 * @mixin \Eloquent
 */
class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'birth_date',
        'about',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
