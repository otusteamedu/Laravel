<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\BusinessModels\Product as BusinessModelsProduct;
use App\Domain\BusinessModels\BaseModel as BusinessBaseModel;
use App\Domain\ValueObjects\Product\ProductDescription;
use App\Domain\ValueObjects\Product\ProductName;
use App\Infrastructure\Helpers\LocaleHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Product extends BaseModel
{
    /**
     * Class Product
     *
     * @property int $id
     * @property string $name_en
     * @property string $name_ru
     * @property text $description_en
     * @property text $description_ru
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photo');
    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'product_recipe');
    }

    public function measureProductRecipes()
    {
        return $this->hasMany(MeasureProductRecipe::class, 'product_id', 'id');
    }

    public function getName(): ProductName 
    {
        $nameField = 'name_' . LocaleHelper::getLocale();
        $productName = new ProductName($this->$nameField);
        return $productName;
    }

    public function getDescription(): ProductDescription 
    {
        $nameField = 'name_' . LocaleHelper::getLocale();
        $productDescription = new ProductDescription($this->$nameField);
        return $productDescription;
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
                'Отсутствует название у продукта с id = ' . $this->getId() . 
                ' по локали: ' . LocaleHelper::getLocale()
            );
            return null;
        } else {
            return new BusinessModelsProduct(
                id:$this->getId(), 
                name:$this->getName(), 
                descripton:$this->getDescription(),
                lang:$this->getLang(), 
                created_at:$this->getCreatedAt()
            );
        }
    }
    
    protected static function newFactory()
    {
        return \Database\Factories\ProductFactory::new();
    }
}
