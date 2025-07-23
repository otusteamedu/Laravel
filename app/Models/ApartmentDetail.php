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

    protected $casts = [
        'registred_qt' => 'integer',
        'lived_qt' => 'integer',
        'total_area' => 'float',
        'personal_account' => 'string',
        'account_number' => 'string',
        'apartment_id' => 'integer',
        'tariff_id' => 'integer',
    ];

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }
}
