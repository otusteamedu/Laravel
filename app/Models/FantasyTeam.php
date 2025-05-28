<?php

namespace App\Models;

use Database\Factories\FantasyTeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FantasyTeam extends BaseModel
{
    /** @use HasFactory<FantasyTeamFactory> */
    use HasFactory;

    public function telegramUser(): BelongsTo
    {
        return $this->belongsTo(TelegramUser::class);
    }

    public function fantasyPlayers(): HasMany
    {
        return $this->HasMany(FantasyTeamPlayer::class);
    }
}
