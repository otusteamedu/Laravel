<?php

namespace App\Infrastructure\Eloquent\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Database\Factories\UserFactory;

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
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

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


    public function getJWTIdentifier(): int
    {
        return $this->id;
    }

    public function getJWTCustomClaims(): array
    {
       return [
           'roles' => $this->roles->pluck('id')->toArray(),
           'email' => $this->email,
           'name' => $this->name,
       ];
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
