<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 * @class Role
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Role extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Роль может принадлежать многим пользователям.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

}
