<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 *
 * @class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
     * Пользователь может иметь много ролей.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Пользователь может иметь много задач.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Проверяет, имеет ли пользователь данное разрешение через любую из своих ролей.
     */
    public function hasPermissionTo($permissionName)
    {
        return $this->roles->contains(function ($role) use ($permissionName) {
            return $role->hasPermissionTo($permissionName);
        });
    }

    /**
     * Проверяет, принадлежит ли пользователь к данной роли.
     */
    public function hasRole($roleName)
    {
        return $this->roles->contains('name', $roleName);
    }

    public function isAdmin(): bool{
       return $this->hasRole('Administrator');
    }


    public function notifications_settings()
    {
        return $this->hasOne(NotificationSettings::class);
    }

}
