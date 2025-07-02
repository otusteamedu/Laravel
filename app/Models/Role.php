<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

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
