<?php

namespace App\Models;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Category extends BaseModel
{
    use HasSlug;

    /** @var bool  */
    public $timestamps = false;

    /** @var string[]  */
    protected $fillable = [
        'name',
        'is_active',
        'sort',
    ];

    protected $columnMap = [
        'id' => 'id',
        'name' => 'name',
        'slug' => 'slug',
        'is_active' => 'is_active',
        'sort' => 'sort',
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

    public function getSlug(): string
    {
        return $this->{$this->getColumnName('slug')};
    }

    public function getIsActive(): bool
    {
        return $this->{$this->getColumnName('is_active')};
    }

    public function getSort(): int
    {
        return $this->{$this->getColumnName('sort')};
    }

    public static function slugFrom(): string
    {
        return 'name';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
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
    public function publishedNews(): HasMany
    {
        return $this->news()->where('is_draft', false);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
