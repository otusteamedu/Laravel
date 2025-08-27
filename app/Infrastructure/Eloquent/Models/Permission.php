<?php

namespace App\Infrastructure\Eloquent\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @class Permission
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Разрешение может принадлежать многим ролям.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}

