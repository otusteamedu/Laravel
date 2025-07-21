<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApartmentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'registred_qt',
        'lived_qt',
        'total_area',
        'personal_account',
        'account_number',
        'apartment_id',
        'tariff_id',
    ];

    // Связь с квартирой
    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    // Связь с тарифом (если модель Tariff есть)
    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }
}