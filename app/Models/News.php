<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class News extends Model
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

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
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
     * @param User|int|string $user
     *
     * @return $this
     */
    public function attachUser(User|int|string $user): News
    {
        $this->user()->associate($user);

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
        return $query->where('is_draft', false);
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
