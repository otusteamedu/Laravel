<?php

namespace App\Models;

use App\Domain\Repositories\Todo\ValueObject\TodoRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property integer $id
 * @property integer $todo_id
 * @property integer $user_id
 * @property TodoRoleEnum $role
 */
class TodoUser extends BaseModel
{
    /** @use HasFactory<\Database\Factories\TodoUserFactory> */
    use HasFactory;

    protected $table = 'todo_user';

    public $timestamps = false;

    public $casts = ['role' => TodoRoleEnum::class];

    public $fillable = ['todo_id', 'user_id', 'role'];
}
