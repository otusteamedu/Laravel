<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tariff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'maintenance',
        'heating',
        'heating_rub',
        'hot_water',
        'hot_water_odn',
        'cold_water',
        'cold_water_odn',
        'sewage',
        'sewage_odn',
        'solid_waste',
        'electricity',
        'lift',
        'electricity_odn',
        'capital_repair',
        'multiplying_factor',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->name = ucfirst($model->name);
        });
    }

    public function __toString()
    {
        return $this->name;
    }
}