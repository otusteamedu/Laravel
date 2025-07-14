<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

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

    protected $columnMap = [
        'id' => 'id',
        'name' => 'name',
        'email' => 'email',
        'password' => 'password',
        'email_verified_at' => 'email_verified_at',
        'subscribed_news' => 'subscribed_news',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    public function getColumnName($property)
    {
        return $this->columnMap[$property] ?? $property;
    }

    public function getId(): int
    {
        return $this->{$this->getColumnName('id')};
    }

    public function getName(): string
    {
        return $this->{$this->getColumnName('name')};
    }

    public function getEmail(): string
    {
        return $this->{$this->getColumnName('email')};
    }

    public function getEmailVerifiedAt(): ?Carbon
    {
        return $this->{$this->getColumnName('email_verified_at')};
    }


    public function getCreatedAt(): ?Carbon {
        return $this->{$this->getColumnName('created_at')};
    }

    public function getUpdatedAt(): ?Carbon {
        return $this->{$this->getColumnName('updated_at')};
    }

    public function getSubscribedNews(): bool {
        return $this->{$this->getColumnName('subscribed_news')};
    }

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
            'subscribed_news' => 'boolean',
        ];
    }


    protected static function booted()
    {
        static::created(function ($user) {
            $role = Role::where('slug', 'user')->first();
            if ($role) {
                $user->roles()->attach($role->id);
            }
        });
    }

    /**
     * @return HasMany
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Проверка наличия роли у пользователя
     *
     * @param ...$roles
     *
     * @return bool
     */
    public function hasRole(string ...$roles): bool
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'admin' => $this->hasRole('admin'),
            'email' => $this->getEmail(),
            'name' => $this->getName(),
        ];
    }
}
