<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\BusinessModels\Measure as BusinessModelsMeasure;
use App\Domain\BusinessModels\BaseModel as BusinessBaseModel;
use App\Domain\ValueObjects\Measure\MeasureName;
use App\Infrastructure\Helpers\LocaleHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Measure extends BaseModel
{
    /**
     * Class Measure
     *
     * @property int $id
     * @property string $name_en
     * @property string $name_ru
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function measureProductRecipes() 
    {
        return $this->hasMany(MeasureProductRecipe::class, 'measure_id', 'id');
    }

    public function getName(): MeasureName 
    {
        $nameField = 'name_' . LocaleHelper::getLocale();
        $measureName = new MeasureName($this->$nameField);
        return $measureName;
    }

    public function getCreatedAt() 
    {
        $data = Carbon::createFromDate($this->created_at)->format('d.m.Y');
        return $data;
    }

    public function getUpdatedAt() 
    {
        $data = Carbon::createFromDate($this->updated_at)->format('d.m.Y');
        return $data;
    }

    public function toBusinessModel(): ?BusinessBaseModel
    {
        if (!$this->getName()->getValue()) {
            Log::warning(
                'Отсутствует название у меры с id = ' . $this->getId() . 
                ' по локали: ' . LocaleHelper::getLocale()
            );
            return null;
        } else {
            return new BusinessModelsMeasure(
                id:$this->getId(), 
                name:$this->getName(), 
                lang:$this->getLang(), 
                created_at:$this->getCreatedAt()
            );
        }
    }
    
    protected static function newFactory()
    {
        return \Database\Factories\MeasureFactory::new();
    }
}
