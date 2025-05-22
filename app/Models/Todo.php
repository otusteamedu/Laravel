<?php

namespace App\Models;

use App\Models\Project;
use App\Models\TodoUser;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property integer $id
 * @property string $title
 * @property integer $author_id
 * @property integer $project_id
 * @property integer $status_id
 * @property string $description
 * @property string $deadline
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $deleted_at
 * @property ?Carbon $options
 * @property User $author
 * @property Project $project
 * @property TodoStatus $status
 * @property TodoUser[] $todoUsers
 * @property TodoComment[] $comments
 * 
 * @method static member(User $user)
 * @method static notMember(User $user)
 */
class Todo extends BaseModel
{
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $table = 'todos';

    public $fillable = ['title', 'author_id', 'project_id', 'status_id', 'description', 'deadline', 'options'];

    protected function casts(): array
    {
        return [
            'options'  => 'json:unicode',
            'deadline' => 'datetime',
        ];
    }

    /**
     * Автор задачи
     * @return BelongsTo<User, Todo>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Проект задачи
     * @return BelongsTo<Project, Todo>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Текущий статус задачи
     * @return BelongsTo<TodoStatus, Todo>
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TodoStatus::class, 'status_id');
    }

    /**
     * Список пользователей имеющих доступ к задаче
     * @return HasMany<TodoUser, Todo>
     */
    public function todoUsers(): HasMany
    {
        return $this->hasMany(TodoUser::class);
    }

    /**
     * Комментарии к задаче
     * @return HasMany<TodoComment, Todo>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TodoComment::class)->orderBy('created_at');
    }

    /**
     * Пользователь является участником задачи
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User $user
     * @return void
     */
    public function scopeMember(Builder $query, User $user): void
    {
        $query->whereHas('todoUsers', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }

    /**
     * Пользователь не является участником задачи
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Models\User $user
     * @return void
     */
    public function scopeNotMember(Builder $query, User $user): void
    {
        $query->whereDoesntHave('todoUsers', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });
    }
}
