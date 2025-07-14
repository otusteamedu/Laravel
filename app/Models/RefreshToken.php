<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class RefreshToken extends Model
{
    protected $fillable = ['user_id', 'token', 'expires_at'];

    protected $dates = ['expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected $columnMap = [
        'id' => 'id',
        'user_id' => 'user_id',
        'token' => 'token',
        'expires_at' => 'expires_at',
    ];

    public function getColumnName($property)
    {
        return $this->columnMap[$property] ?? $property;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->getExpiresAt()->isPast();
    }


    public function getId(): int
    {
        return $this->{$this->getColumnName('id')};
    }

    public function getUserId(): int
    {
        return $this->{$this->getColumnName('user_id')};
    }

    public function getToken(): string
    {
        return $this->{$this->getColumnName('token')};
    }

    public function getExpiresAt(): ?Carbon
    {
        return $this->{$this->getColumnName('expires_at')};
    }
}
