<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /** @var string[]  */
    protected $fillable = [
        'text',
    ];

    /**
     * @return belongsTo
     */
    public function parent(): belongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    /**
     * Один дочерний уровень
     *
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Получение нескольких дочерних уровней
     *
     * @return HasMany
     */
    public function childrenComments(): HasMany
    {
        return $this->hasMany(Comment::class)->with('comments');
    }

    /**
     * Получить новость, которому принадлежит комментарий.
     *
     * @return BelongsTo
     */
    public function newsItem(): BelongsTo
    {
        return $this->belongsTo(News::class, 'news_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
