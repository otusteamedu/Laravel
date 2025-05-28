<?php

namespace App\Models;

use Database\Factories\TelegramUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TelegramUser extends BaseModel
{
    /** @use HasFactory<TelegramUserFactory> */
    use HasFactory;

    public function fantasyTeam(): HasOne
    {
        return $this->hasOne(FantasyTeam::class);
    }
}
