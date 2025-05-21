<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ImageFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * Изображения
 *
 * @property int $id
 * @property string $path Путь к изображению
 * @property bool $main Основное изображение
 * @property string $image_type
 * @property int $image_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static ImageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereImageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Image whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $image
 * @mixin Eloquent
 */
class Image extends BaseModel
{
    /** @use HasFactory<ImageFactory> */
    use hasFactory;

    protected $fillable = [
        'path',
        'main',
        'image_type',
        'image_id',
    ];

    public function image(): MorphTo
    {
        return $this->morphTo();
    }
}
