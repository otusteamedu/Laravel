<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }
}