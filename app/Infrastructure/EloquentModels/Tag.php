<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\BusinessModels\BaseModel as BusinessBaseModel;
use App\Domain\BusinessModels\Tag as BusinessModelsTag;
use App\Domain\ValueObjects\Tag\TagName;
use App\Infrastructure\Helpers\LocaleHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Tag extends BaseModel
{
    /**
     * Class Tag
     *
     * @property int $id
     * @property string $name_en
     * @property string $name_ru
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_tag');
    }

    public function getName(): TagName 
    {
        $nameField = 'name_' . LocaleHelper::getLocale();
        $tagName = new TagName($this->$nameField);
        return $tagName;
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
            return new BusinessModelsTag(
                id:$this->getId(), 
                name:$this->getName(), 
                lang:$this->getLang(), 
                created_at:$this->getCreatedAt()
            );
        }
    }
    
    protected static function newFactory()
    {
        return \Database\Factories\TagFactory::new();
    }
}
