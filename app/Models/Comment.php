<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Comment extends BaseModel
{
    use HasFactory;

    /** @var string[]  */
    protected $fillable = [
        'text',
    ];


    protected $columnMap = [
        'id' => 'id',
        'comment_id' => 'comment_id',
        'user_id' => 'user_id',
        'news_id' => 'news_id',
        'text' => 'text',
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

    public function getText(): string
    {
        return $this->{$this->getColumnName('text')};
    }

    public function getParentId(): int
    {
        return $this->{$this->getColumnName('comment_id')};
    }

    public function getUserId(): int
    {
        return $this->{$this->getColumnName('user_id')};
    }

    public function getNewsId(): int
    {
        return $this->{$this->getColumnName('news_id')};
    }

    public function getCreatedAt(): ?Carbon {
        return $this->{$this->getColumnName('created_at')};
    }


    public function getUpdatedAt(): ?Carbon {
        return $this->{$this->getColumnName('updated_at')};
    }

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
     * Получить новость, которой принадлежит комментарий
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
