<?php

namespace App\Models;

use App\Models\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasSlug;

    /** @var bool  */
    public $timestamps = false;

    /** @var string[]  */
    protected $fillable = [
        'name',
        'sort',
    ];

    public static function slugFrom(): string
    {
        return 'name';
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
}
