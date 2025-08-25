<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\BusinessModels\Area as BusinessModelArea;
use App\Domain\BusinessModels\BaseModel as BusinessBaseModel;
use App\Infrastructure\Helpers\LocaleHelper;
use App\Domain\ValueObjects\Lang;
use App\Domain\ValueObjects\Area\AreaName;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Area extends BaseModel implements EloquentModelsInterface
{
    /**
     * Class Area
     *
     * @property int $id
     * @property string $name_en
     * @property string $name_ru
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */
    
    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'area_id', 'id');
    }

    public function getName(): AreaName 
    {
        $nameField = 'name_' . LocaleHelper::getLocale();
        $areaName = new AreaName($this->$nameField);
        return $areaName;
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
            return new BusinessModelArea(
                id:$this->getId(), 
                name:$this->getName(), 
                lang:$this->getLang(), 
                created_at:$this->getCreatedAt()
            );
        }
    }
    
    protected static function newFactory()
    {
        return \Database\Factories\AreaFactory::new();
    }
}
