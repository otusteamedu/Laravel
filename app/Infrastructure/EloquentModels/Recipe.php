<?php

namespace App\Infrastructure\EloquentModels;

use App\Domain\BusinessModels\Area;
use App\Domain\BusinessModels\BaseModel as BusinessBaseModel;
use App\Domain\BusinessModels\Category;
use App\Domain\BusinessModels\Recipe as BusinessModelsRecipe;
use App\Domain\ValueObjects\Recipe\RecipeInstruction;
use App\Domain\ValueObjects\Recipe\RecipeName;
use App\Infrastructure\Helpers\LocaleHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Recipe extends BaseModel
{
    /**
     * Class Recipe
     *
     * @property int $id
     * @property string $api_id
     * @property string $name_en
     * @property string $name_ru
     * @property string $alternate
     * @property int $category_id
     * @property text $instruction_en
     * @property text $instruction_ru
     * @property int $area_id
     * @property \Illuminate\Support\Carbon $created_at
     * @property \Illuminate\Support\Carbon $updated_at
     */

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photo');
    }

    public function videos()
    {
        return $this->morphOne(Video::class, 'video');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'recipe_tag');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_recipe');
    }

    public function measureProductRecipes() 
    {
        return $this->hasMany(MeasureProductRecipe::class, 'recipe_id', 'id');
    }

    public function getApiId(): string 
    {
        return $this->api_id;
    }

    public function getName(): RecipeName 
    {
        $nameField = 'name_' . LocaleHelper::getLocale();
        $recipeName = new RecipeName($this->$nameField);
        return $recipeName;
    }

    public function getAlternate(): string 
    {
        return $this->alternate;
    }

    public function getCategory(): Category 
    {
        $categoryEloquent = $this->category;
        $category = $categoryEloquent->toBusinessModel();
        return $category;
    }

    public function getInstruction(): RecipeInstruction 
    {
        $nameField = 'instruction_' . LocaleHelper::getLocale();
        $recipeInstruction = new RecipeInstruction($this->$nameField);
        return $recipeInstruction;
    }

    public function getAria(): Area 
    {
        $areaEloquent = $this->area;
        $area = $areaEloquent->toBusinessModel();
        return $area;
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
            return new BusinessModelsRecipe(
                id:$this->getId(), 
                name:$this->getName(), 
                instruction:$this->getInstruction(), 
                lang:$this->getLang(), 
                apiId:$this->getApiId(),
                alternate:$this->getAlternate(),
                category:$this->getCategory(),
                area:$this->getAria(),
                created_at:$this->getCreatedAt()
            );
        }
    }
    
    protected static function newFactory()
    {
        return \Database\Factories\RecipeFactory::new();
    }
}
