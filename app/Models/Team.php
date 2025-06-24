<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id;
 * @property string $nickname;
 * @property string $name;
 * @property string|null $logo_path;
 * @property Carbon $created_at;
 */
class Team extends BaseModel
{
    /** @use HasFactory<TeamFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'nickname',
        'logo_path'
    ];

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    protected static function booted(): void
    {
        static::created(function () {
            Cache::forget('teams');
        });

        static::updated(function () {
            Cache::forget('teams');
        });

        static::deleted(function () {
            Cache::forget('teams');
        });
    }
}
