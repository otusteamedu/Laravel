<?php

namespace App\Models;

use Carbon\Carbon;

class Area extends BaseModel
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

    public function getId() 
    {
        return $this->id;
    }

    public function getName() 
    {
        $nameField = 'name_' . config('app.locale');
        return $this->$nameField;
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
