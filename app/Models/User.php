<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Project;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property string $created_at
 * @property string $updated_at
 * @property UserProfile $profile
 * @property Project[] $allProjects
 * @property Project[] $activeProjects
 * @property UserSocialite[] $socialites
 * @property Todo[] $authorTodos
 * @property Todo[] $todos
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Дополнительные поля профиля пользователя
     * @return HasOne<UserProfile, User>
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Проекты в которые пользователь был приглашен, является или являлся участником
     * @return BelongsToMany<Project, User, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, ProjectUser::class)
            ->withPivot('roles', 'invited_at', 'joined_at', 'left_at');
    }

    /**
     * Проекты в которых пользователь является участником в настоящий момент
     * @return BelongsToMany<Project, User, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function activeProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, ProjectUser::class)
            ->withPivot('roles', 'invited_at', 'joined_at')
            ->wherePivotNull('left_at');
    }

    /**
     * Соцсети, через которые пользователь авторизовывался
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<UserSocialite, User>
     */
    public function socialites(): HasMany
    {
        return $this->hasMany(UserSocialite::class);
    }

    /**
     * Задачи созданные пользователем
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Todo, User>
     */
    public function authorTodos(): HasMany
    {
        return $this->hasMany(Todo::class, 'author_id');
    }

    /**
     * Задачи в которых учавствует пользователь
     * @return BelongsToMany<Todo, User, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function todos(): BelongsToMany
    {
        return $this->belongsToMany(Todo::class, TodoUser::class)
            ->withPivot('roles');
    }
}
