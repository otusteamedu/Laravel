<?php

namespace App\Models;

use App\Events\ProductPriceChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @class Product
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property string|null $text
 * @property string|null $image
 * @property string|null $images // Stored as JSON string in DB, accessed as array
 * @property int $is_sale // Stored as tinyInteger, accessed as bool
 * @property int $published // Stored as tinyInteger, accessed as bool
 * @property int $order
 * @property float $price
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Product extends BaseModel
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    /**
     * Temporary storage for price change data
     *
     * @var array|null
     */
    private $priceChangeData = null;


    protected $fillable = [
        'title',
        'alias',
        'text',
        'image',
        'images',
        'is_sale',
        'published',
        'order',
        'price',
    ];



    public static function boot()
    {
        parent::boot();

        static::updating(function ($product) {
            // Проверяем, изменилась ли цена
            if ($product->isDirty('price')) {
                $oldPrice = $product->getOriginal('price');
                $newPrice = $product->price;

                // Проверяем, что цена действительно изменилась (не просто обновление без изменений)
                if ($oldPrice != $newPrice) {
                    // Сохраняем данные для события после сохранения модели
                    $product->setPriceChangeData([
                        'old_price' => $oldPrice,
                        'new_price' => $newPrice
                    ]);
                }
            }
        });

        static::updated(function ($product) {
            // Если есть данные об изменении цены, отправляем событие
            if ($product->hasPriceChangeData()) {
                $priceChangeData = $product->getPriceChangeData();

                event(new ProductPriceChanged(
                        $product,
                        $priceChangeData['old_price'],
                        $priceChangeData['new_price']
                    )
                );

                /*
                ProductPriceChanged::dispatch(
                    $product,
                    $priceChangeData['old_price'],
                    $priceChangeData['new_price']
                );*/

                // Очищаем временные данные
                $product->clearPriceChangeData();
            }
        });
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    /**
     * Set price change data
     */
    private function setPriceChangeData(array $data): void
    {
        $this->priceChangeData = $data;
    }

    /**
     * Get price change data
     */
    private function getPriceChangeData(): ?array
    {
        return $this->priceChangeData;
    }

    /**
     * Check if has price change data
     */
    private function hasPriceChangeData(): bool
    {
        return !is_null($this->priceChangeData);
    }

    /**
     * Clear price change data
     */
    private function clearPriceChangeData(): void
    {
        $this->priceChangeData = null;
    }
}
