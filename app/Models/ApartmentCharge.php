<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }
}