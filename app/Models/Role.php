<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Role model.
 *
 * @property int $id Role ID
 * @property string $title Role name
 * @property \Illuminate\Support\Carbon $created_at Creation date
 * @property \Illuminate\Support\Carbon $updated_at Last update date
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<\App\Models\User> $users Users with this role
 */
class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    protected $guarded = [];
    protected $table = 'roles';

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): \Illuminate\Support\Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): \Illuminate\Support\Carbon
    {
        return $this->updated_at;
    }

    public function getUsers(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->users;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
