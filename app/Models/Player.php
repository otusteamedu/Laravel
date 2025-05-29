<?php

namespace App\Models;

use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id;
 * @property string $nickname;
 * @property string $name;
 * @property string $position;
 * @property int $team_id;
 * @property int $price;
 * @property string $avatar_path;
 * @property int $created_at;
 */
class Player extends BaseModel
{
    /** @use HasFactory<PlayerFactory> */
    use HasFactory;

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function fantasyPlayer(): HasOne
    {
        return $this->hasOne(FantasyTeamPlayer::class);
    }
}
