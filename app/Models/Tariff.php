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

    protected $casts = [
        'name' => 'string',
        'maintenance' => 'float',
        'heating' => 'float',
        'heating_rub' => 'float',
        'hot_water' => 'float',
        'hot_water_odn' => 'float',
        'cold_water' => 'float',
        'cold_water_odn' => 'float',
        'sewage' => 'float',
        'sewage_odn' => 'float',
        'solid_waste' => 'float',
        'electricity' => 'float',
        'lift' => 'float',
        'electricity_odn' => 'float',
        'capital_repair' => 'float',
        'multiplying_factor' => 'float',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->name = ucfirst($model->name);
        });
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
