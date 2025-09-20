<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\BusinessModels\BaseModel as BusinessBaseModel;
use App\Domain\BusinessModels\Category as BusinessModelsCategory;
use App\Domain\ValueObjects\Category\CategoryDescription;
use App\Domain\ValueObjects\Category\CategoryName;
use App\Infrastructure\Helpers\LocaleHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Category extends BaseModel
{
    /**
     * Class Category
     *
     * @property int $id
     * @property string $api_id
     * @property string $name_en
     * @property string $name_ru
     * @property text $description_en
     * @property text $description_ru
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'category_id', 'id');
    }

    public function getName(): CategoryName
    {
        $nameField = 'name_' . LocaleHelper::getLocale();
        $categoryName = new CategoryName($this->$nameField);
        return $categoryName;
    }

    public function getApiId(): string
    {
        return $this->api_id;
    }

    public function getDescription(): CategoryDescription
    {
        $nameField = 'name_' . LocaleHelper::getLocale();
        $categoryDescription = new CategoryDescription($this->$nameField);
        return $categoryDescription;
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
                'Отсутствует название у категории с id = ' . $this->getId() .
                    ' по локали: ' . LocaleHelper::getLocale()
            );
            return null;
        } else {
            return new BusinessModelsCategory(
                id: $this->getId(),
                apiId:$this->getApiId(),
                name: $this->getName(),
                description: $this->getDescription(),
                lang: $this->getLang(),
                created_at: $this->getCreatedAt()
            );
        }
    }

    protected static function newFactory()
    {
        return \Database\Factories\CategoryFactory::new();
    }
}
