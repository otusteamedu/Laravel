<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ItemFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;

/**
 * Товары
 *
 * @property int $id
 * @property string $name Название товара
 * @property string $description Описание товара
 * @property int $price Цена товара
 * @property string $address Адрес
 * @property int $user_id ID пользователя
 * @property int $currency_id ID валюты
 * @property int $category_id ID категории
 * @property int $country_id ID страны
 * @property int $region_id ID региона
 * @property int $city_id ID города
 * @property bool $is_new Новый/БУ
 * @property bool $is_moderated Прошёл модерацию
 * @property bool $is_published Опубликован
 * @property string|null $published_until Опубликован до
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static ItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereIsModerated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereIsNew($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereIsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item wherePublishedUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereUserId($value)
 * @property-read Category $category
 * @property-read City $city
 * @property-read Country $country
 * @property-read Currency $currency
 * @property-read string $full_address
 * @property-read string $price_with_currency
 * @property-read Region $region
 * @property-read User $user
 * @property-read Collection<int, Image> $images
 * @property-read int|null $images_count
 * @mixin Eloquent
 */
class Item extends BaseModel
{
    /** @use HasFactory<ItemFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'address',
        'user_id',
        'currency_id',
        'category_id',
        'country_id',
        'region_id',
        'city_id',
        'is_new',
        'is_moderated',
        'is_published',
        'published_until',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'is_moderated' => 'boolean',
        'is_published' => 'boolean',
        'published_until' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'image');
    }
}
