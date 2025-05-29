<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
