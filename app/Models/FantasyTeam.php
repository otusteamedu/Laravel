<?php

namespace App\Models;

use Database\Factories\FantasyTeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property $id;
 * @property $telegram_user_id;
 * @property string $name;
 * @property int $budget;
 * @property int $points;
 * @property int $created_at;
 */
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
