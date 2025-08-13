<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Class RefreshToken
 *
 * @package App\Models
 *
 * @property int $id Идентификатор токена.
 * @property int $user_id Идентификатор пользователя, которому принадлежит токен.
 * @property string $token Уникальная строка токена.
 * @property Carbon $expires_at Дата и время истечения срока действия токена.
 * @property Carbon|null $created_at Дата и время создания токена.
 * @property Carbon|null $updated_at Дата и время последнего обновления токена.
 *
 * @mixin Builder
 */
class RefreshToken extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно массово присваивать.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    /**
     * Преобразование атрибутов.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the refresh token.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the refresh token is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Scope to get only valid (non-expired) tokens.
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }
}
