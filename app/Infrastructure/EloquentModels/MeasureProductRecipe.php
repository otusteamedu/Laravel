<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\BusinessModels\MeasureProductRecipe as BusinessModelMeasureProductRecipe;
use App\Domain\BusinessModels\BaseModel as BusinessBaseModel;
use App\Domain\ValueObjects\MeasureProductRecipe\MeasureProductRecipeValue;
use App\Infrastructure\Helpers\LocaleHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MeasureProductRecipe extends BaseModel
{
    protected $table = 'measure_product_recipe';

    /**
     * Class MeasureProductRecipe
     *
     * @property int $id
     * @property int $product_id
     * @property int $recipe_id
     * @property int $measure_id
     * @property string $value
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipe_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function measure()
    {
        return $this->belongsTo(Measure::class, 'measure_id', 'id');
    }

    public function getRecipeId()
    {
        return $this->recipe_id;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function getMeasureId()
    {
        return $this->measure_id;
    }

    public function getValue()
    {
        return new MeasureProductRecipeValue($this->value);
    }

    public function getCreatedAt(): string
    {
        $data = Carbon::createFromDate($this->created_at)->format('d.m.Y');
        return $data;
    }

    public function getUpdatedAt(): string
    {
        $data = Carbon::createFromDate($this->updated_at)->format('d.m.Y');
        return $data;
    }

    public function toBusinessModel(): ?BusinessBaseModel
    {
        if (!$this->getName()->getValue()) {
            Log::warning(
                'Отсутствует название у территории с id = ' . $this->getId() .
                    ' по локали: ' . LocaleHelper::getLocale()
            );
            return null;
        } else {
            return new BusinessModelMeasureProductRecipe(
                id: $this->getId(),
                value: $this->getValue(),
                created_at: $this->getCreatedAt()
            );
        }
    }

    protected static function newFactory()
    {
        return \Database\Factories\MeasureProductRecipeFactory::new();
    }
}
