<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property float $hot_water_previous
 * @property float $hot_water_current
 * @property float $hot_water_value
 * @property float $cold_water_previous
 * @property float $cold_water_current
 * @property float $cold_water_value
 * @property float $electricity_previous
 * @property float $electricity_current
 * @property float $electricity_value
 * @property float $wastewater_value
 * @property int $apartment_id
 * @property-read \App\Models\Apartment $apartment
 */
class ApartmentCounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'hot_water_previous',
        'hot_water_current',
        'hot_water_value',
        'cold_water_previous',
        'cold_water_current',
        'cold_water_value',
        'electricity_previous',
        'electricity_current',
        'electricity_value',
        'wastewater_value',
        'apartment_id',
    ];

    /**
     * @return BelongsTo<\App\Models\Apartment, self>
     */
    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }

    public function getHotWaterPrevious(): float
    {
        return (float) $this->attributes['hot_water_previous'];
    }

    public function getHotWaterCurrent(): float
    {
        return (float) $this->attributes['hot_water_current'];
    }

    public function getHotWaterValue(): float
    {
        return (float) $this->attributes['hot_water_value'];
    }

    public function getColdWaterPrevious(): float
    {
        return (float) $this->attributes['cold_water_previous'];
    }

    public function getColdWaterCurrent(): float
    {
        return (float) $this->attributes['cold_water_current'];
    }

    public function getColdWaterValue(): float
    {
        return (float) $this->attributes['cold_water_value'];
    }

    public function getElectricityPrevious(): float
    {
        return (float) $this->attributes['electricity_previous'];
    }

    public function getElectricityCurrent(): float
    {
        return (float) $this->attributes['electricity_current'];
    }

    public function getElectricityValue(): float
    {
        return (float) $this->attributes['electricity_value'];
    }

    public function getWastewaterValue(): float
    {
        return (float) $this->attributes['wastewater_value'];
    }

    public function getApartmentId(): int
    {
        return (int) $this->attributes['apartment_id'];
    }

    public function getApartment(): \App\Domain\Apartment\Apartment
    {
        return $this->apartment;
    }
}
