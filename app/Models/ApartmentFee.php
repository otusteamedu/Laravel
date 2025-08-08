<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Domain\Apartment\Apartment;

/**
 * @property float $maintenance
 * @property float $electricity_odn
 * @property float $lift
 * @property float $maintenance_full
 * @property float $solid_waste
 * @property float $electricity
 * @property float $heating
 * @property float $heating_rub
 * @property float $hot_water
 * @property float $hot_water_odn
 * @property float $cold_water
 * @property float $cold_water_odn
 * @property float $sewage
 * @property float $sewage_odn
 * @property float $maintenance_total
 * @property float $accrued_expenses
 * @property float $recalculation
 * @property float $balance_start
 * @property float $balance_end
 * @property float $paid
 * @property float $fine
 * @property float $total
 * @property int $apartment_id
 * @property-read \App\Models\Apartment $apartment
 */
class ApartmentFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance',
        'electricity_odn',
        'lift',
        'maintenance_full',
        'solid_waste',
        'electricity',
        'heating',
        'heating_rub',
        'hot_water',
        'hot_water_odn',
        'cold_water',
        'cold_water_odn',
        'sewage',
        'sewage_odn',
        'maintenance_total',
        'accrued_expenses',
        'recalculation',
        'balance_start',
        'balance_end',
        'paid',
        'fine',
        'total',
        'apartment_id',
    ];

    /**
     * @return BelongsTo<\App\Models\Apartment, self>
     */
    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function getMaintenance(): float
    {
        return (float) $this->attributes['maintenance'];
    }

    public function getElectricityOdn(): float
    {
        return (float) $this->attributes['electricity_odn'];
    }

    public function getLift(): float
    {
        return (float) $this->attributes['lift'];
    }

    public function getMaintenanceFull(): float
    {
        return (float) $this->attributes['maintenance_full'];
    }

    public function getSolidWaste(): float
    {
        return (float) $this->attributes['solid_waste'];
    }

    public function getElectricity(): float
    {
        return (float) $this->attributes['electricity'];
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

    public function getMaintenanceTotal(): float
    {
        return (float) $this->attributes['maintenance_total'];
    }

    public function getAccruedExpenses(): float
    {
        return (float) $this->attributes['accrued_expenses'];
    }

    public function getRecalculation(): float
    {
        return (float) $this->attributes['recalculation'];
    }

    public function getBalanceStart(): float
    {
        return (float) $this->attributes['balance_start'];
    }

    public function getBalanceEnd(): float
    {
        return (float) $this->attributes['balance_end'];
    }

    public function getPaid(): float
    {
        return (float) $this->attributes['paid'];
    }

    public function getFine(): float
    {
        return (float) $this->attributes['fine'];
    }

    public function getTotal(): float
    {
        return (float) $this->attributes['total'];
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
