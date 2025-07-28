<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property string $name
 * @property float $maintenance
 * @property float $heating
 * @property float $heating_rub
 * @property float $hot_water
 * @property float $hot_water_odn
 * @property float $cold_water
 * @property float $cold_water_odn
 * @property float $sewage
 * @property float $sewage_odn
 * @property float $solid_waste
 * @property float $electricity
 * @property float $lift
 * @property float $electricity_odn
 * @property float $capital_repair
 * @property float $multiplying_factor
 */
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

    public function getName(): string
    {
        return (string) $this->attributes['name'];
    }

    public function getMaintenance(): float
    {
        return (float) $this->attributes['maintenance'];
    }

    public function getHeating(): float
    {
        return (float) $this->attributes['heating'];
    }

    public function getHeatingRub(): float
    {
        return (float) $this->attributes['heating_rub'];
    }

    public function getHotWater(): float
    {
        return (float) $this->attributes['hot_water'];
    }

    public function getHotWaterOdn(): float
    {
        return (float) $this->attributes['hot_water_odn'];
    }

    public function getColdWater(): float
    {
        return (float) $this->attributes['cold_water'];
    }

    public function getColdWaterOdn(): float
    {
        return (float) $this->attributes['cold_water_odn'];
    }

    public function getSewage(): float
    {
        return (float) $this->attributes['sewage'];
    }

    public function getSewageOdn(): float
    {
        return (float) $this->attributes['sewage_odn'];
    }

    public function getSolidWaste(): float
    {
        return (float) $this->attributes['solid_waste'];
    }

    public function getElectricity(): float
    {
        return (float) $this->attributes['electricity'];
    }

    public function getLift(): float
    {
        return (float) $this->attributes['lift'];
    }

    public function getElectricityOdn(): float
    {
        return (float) $this->attributes['electricity_odn'];
    }

    public function getCapitalRepair(): float
    {
        return (float) $this->attributes['capital_repair'];
    }

    public function getMultiplyingFactor(): float
    {
        return (float) $this->attributes['multiplying_factor'];
    }
}
