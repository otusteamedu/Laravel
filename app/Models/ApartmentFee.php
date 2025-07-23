<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $casts = [
        'maintenance' => 'float',
        'electricity_odn' => 'float',
        'lift' => 'float',
        'maintenance_full' => 'float',
        'solid_waste' => 'float',
        'electricity' => 'float',
        'heating' => 'float',
        'heating_rub' => 'float',
        'hot_water' => 'float',
        'hot_water_odn' => 'float',
        'cold_water' => 'float',
        'cold_water_odn' => 'float',
        'sewage' => 'float',
        'sewage_odn' => 'float',
        'maintenance_total' => 'float',
        'accrued_expenses' => 'float',
        'recalculation' => 'float',
        'balance_start' => 'float',
        'balance_end' => 'float',
        'paid' => 'float',
        'fine' => 'float',
        'total' => 'float',
        'apartment_id' => 'integer',
    ];

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }
}
