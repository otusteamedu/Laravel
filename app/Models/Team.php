<?php

namespace App\Models;

use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends BaseModel
{
    /** @use HasFactory<TeamFactory> */
    use HasFactory;

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }
}
