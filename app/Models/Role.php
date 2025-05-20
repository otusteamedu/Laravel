<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Role model.
 * 
 * @property-read int $id Role ID
 * @property-read string $title Role name
 * @property-read \Illuminate\Support\Carbon $created_at Creation date
 * @property-read \Illuminate\Support\Carbon $updated_at Last update date
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
        return $this->attributes['id'];
    }

    public function getTitle(): string
    {
        return $this->attributes['title'];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
