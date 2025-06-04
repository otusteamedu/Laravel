<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
