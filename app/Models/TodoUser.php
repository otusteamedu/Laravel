<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property integer $id
 * @property integer $todo_id
 * @property integer $user_id
 * @property string $roles
 */
class TodoUser extends Pivot
{
    /** @use HasFactory<\Database\Factories\TodoUserFactory> */
    use HasFactory;

    protected $table = 'todo_user';

    public $timestamps = false;

    public $fillable = ['todo_id', 'user_id', 'roles'];

    protected function roles(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value, JSON_UNESCAPED_UNICODE),
        );
    }
}
