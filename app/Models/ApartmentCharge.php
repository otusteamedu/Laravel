<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property float $money_deposited
 * @property float $fine
 * @property float $recalculation_electricity
 * @property float $recalculation_heating_rub
 * @property float $recalculation_hot_water
 * @property float $recalculation_cold_water
 * @property float $recalculation_sewage
 * @property float $recalculation_solid_waste
 * @property float $recalculation_maintenance
 * @property float $balance_start
 * @property int $apartment_id
 * @property-read \App\Models\Apartment $apartment
 */
class ApartmentCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'money_deposited',
        'fine',
        'recalculation_electricity',
        'recalculation_heating_rub',
        'recalculation_hot_water',
        'recalculation_cold_water',
        'recalculation_sewage',
        'recalculation_solid_waste',
        'recalculation_maintenance',
        'balance_start',
        'apartment_id',
    ];

    /**
     * @return BelongsTo<\App\Models\Apartment, self>
     */
    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function getMoneyDeposited(): float
    {
        return (float) $this->attributes['money_deposited'];
    }

    public function getFine(): float
    {
        return (float) $this->attributes['fine'];
    }

    public function getRecalculationElectricity(): float
    {
        return (float) $this->attributes['recalculation_electricity'];
    }

    public function getRecalculationHeatingRub(): float
    {
        return (float) $this->attributes['recalculation_heating_rub'];
    }

    public function getRecalculationHotWater(): float
    {
        return (float) $this->attributes['recalculation_hot_water'];
    }

    public function getRecalculationColdWater(): float
    {
        return (float) $this->attributes['recalculation_cold_water'];
    }

    public function getRecalculationSewage(): float
    {
        return (float) $this->attributes['recalculation_sewage'];
    }

    public function getRecalculationSolidWaste(): float
    {
        return (float) $this->attributes['recalculation_solid_waste'];
    }

    public function getRecalculationMaintenance(): float
    {
        return (float) $this->attributes['recalculation_maintenance'];
    }

    public function getBalanceStart(): float
    {
        return (float) $this->attributes['balance_start'];
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
