<?php

namespace App\Models;

use Database\Factories\UserRoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property int $created_at
 */
class UserRole extends BaseModel
{
    /** @use HasFactory<UserRoleFactory> */
    use HasFactory;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
