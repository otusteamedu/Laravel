<?php

namespace App\TodoApp\Infrastructure\Eloquent\Models;

use App\Models\BaseModel;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property integer $id
 * @property integer $project_id
 * @property integer $user_id
 * @property array $roles
 * @property ?Carbon $invited_at
 * @property ?Carbon $joined_at
 * @property ?Carbon $left_at
 */
class ProjectUser extends BaseModel
{
    /** @use HasFactory<\Database\Factories\ProjectUserFactory> */
    use HasFactory;

    protected $table = 'project_user';

    public $timestamps = false;

    public $fillable = ['project_id', 'user_id', 'roles', 'invited_at', 'joined_at', 'left_at'];

    protected function casts(): array
    {
        return [
            'invited_at' => 'datetime',
            'joined_at'  => 'datetime',
            'left_at'    => 'datetime',
        ];
    }

    protected function roles(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value, JSON_UNESCAPED_UNICODE),
        );
    }
}
