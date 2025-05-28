<?php

namespace App\Models;

use Database\Factories\FantasyTeamPlayerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
