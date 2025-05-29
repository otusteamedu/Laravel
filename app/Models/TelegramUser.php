<?php

namespace App\Models;

use Database\Factories\TelegramUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $telegram_id;
 * @property string|null $username;
 * @property string $first_name;
 * @property string|null $last_name;
 * @property int $created_at;
 */
class TelegramUser extends BaseModel
{
    /** @use HasFactory<TelegramUserFactory> */
    use HasFactory;

    public function fantasyTeam(): HasOne
    {
        return $this->hasOne(FantasyTeam::class);
    }
}
