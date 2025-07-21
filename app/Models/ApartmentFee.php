<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApartmentFee extends Model
{
    use HasFactory;

    // app/Models/ApartmentFee.php

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
        'apartment_id'
    ];

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }
}