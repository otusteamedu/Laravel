<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property integer $id
 * @property string $name
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $deleted_at
 * @property User[] $users
 * @property ProjectUser[] $userProjects
 * @property TodoStatus[] $todoStatuses
 * @property Todo[] $todos
 */

class Project extends BaseModel
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'projects';

    public $fillable = ['name'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, ProjectUser::class);
    }

    public function userProjects(): HasMany
    {
        return $this->hasMany(ProjectUser::class);
    }

    /**
     * Список статусов задач проекта
     * @return HasMany<TodoStatus, Project>
     */
    public function todoStatuses(): HasMany
    {
        return $this->hasMany(TodoStatus::class, 'status_id')->orderBy('sort');
    }

    /**
     * Список задач проекта
     * @return HasMany<Todo, Project>
     */
    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class)->orderByDesc('updated_at');
    }
}
