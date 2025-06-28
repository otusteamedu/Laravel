<?php

namespace App\Models;

use Carbon\Carbon;

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

    public function getId() 
    {
        return $this->id;
    }

    public function getName() 
    {
        $name = 'name_' . config('app.locale');
        return $this->$name;
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
}
