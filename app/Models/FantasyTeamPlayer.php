<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\FantasyTeamPlayerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int id;
 * @property int $player_id;
 * @property int $fantasy_team_id;
 * @property Carbon $created_at;
 */
class FantasyTeamPlayer extends BaseModel
{
    /** @use HasFactory<FantasyTeamPlayerFactory> */
    use HasFactory;

    public function fantasyTeams(): BelongsToMany
    {
        return $this->belongsToMany(FantasyTeam::class);
    }

    public function player(): BelongsTo
    {
        return $this->BelongsTo(Player::class);
    }

}
