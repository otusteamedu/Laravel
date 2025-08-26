<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class News extends BaseModel
{
    use HasFactory;


    /** @var string[]  */
    protected $fillable = [
        'title',
        'content',
        'thumbnail',
        'is_draft',
        'published_at',
    ];


    protected $columnMap = [
        'id' => 'id',
        'title' => 'title',
        'thumbnail' => 'thumbnail',
        'content' => 'content',
        'publishedAt' => 'published_at',
        'is_draft' => 'is_draft',
        'author_id' => 'author_id',
        'category_id' => 'category_id',
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

    public function getTitle(): string
    {
        return $this->{$this->getColumnName('title')};
    }

    public function getContent(): string
    {
        return $this->{$this->getColumnName('content')};
    }


    public function getThumbnail(): ?string
    {
        return $this->{$this->getColumnName('thumbnail')};
    }

    public function getIsDraft(): bool
    {
        return $this->{$this->getColumnName('is_draft')};
    }


    public function getAuthorId(): int
    {
        return $this->{$this->getColumnName('author_id')};
    }

    public function getCategoryId(): int
    {
        return $this->{$this->getColumnName('category_id')};
    }

    public function getPublishedAt(): ?Carbon {
        return $this->{$this->getColumnName('published_at')};
    }


    public function getCreatedAt(): ?Carbon {
        return $this->{$this->getColumnName('created_at')};
    }


    public function getUpdatedAt(): ?Carbon {
        return $this->{$this->getColumnName('updated_at')};
    }


    /**
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'author_id','id');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->BelongsTo(Category::class);
    }


    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'is_draft' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * @param User|int|string $author
     *
     * @return $this
     */
    public function attachAuthor(User|int|string $author): News
    {
        $this->author()->associate($author);

        return $this;
    }

    /**
     * @param Category|int|string $category
     *
     * @return $this
     */
    public function attachCategory(Category|int|string $category): News
    {
        $this->category()->associate($category);

        return $this;
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_draft', false)->where('published_at', '<=', Carbon::now());
    }

    /**
     * @param Builder $query
     * @param int     $categoryId
     *
     * @return Builder
     */
    public function scopeOfCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * @param Builder $query
     * @param int     $userId
     *
     * @return Builder
     */
    public function scopeOfUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * @param Builder $query
     * @param Carbon  $from
     * @param Carbon  $to
     *
     * @return Builder
     */
    public function scopeBetweenDates(Builder $query, Carbon $from, Carbon $to): Builder
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    /**
     * @param Builder $query
     * @param string  $term
     *
     * @return Builder
     */
    public function scopeSearchByTitle(Builder $query, string $term): Builder
    {
        return $query->where('title', 'like', '%' . $term . '%');
    }
}
