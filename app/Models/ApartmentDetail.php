<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $registred_qt
 * @property int $lived_qt
 * @property float $total_area
 * @property string $personal_account
 * @property string $account_number
 * @property int $apartment_id
 * @property int $tariff_id
 * @property-read \App\Models\Apartment $apartment
 * @property-read \App\Models\Tariff $tariff
 */
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

    /**
     * @return BelongsTo<\App\Models\Apartment, self>
     */
    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class);
    }

    /**
     * @return BelongsTo<\App\Models\Tariff, self>
     */
    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }

    public function getRegistredQt(): int
    {
        return (int) $this->attributes['registred_qt'];
    }

    public function getLivedQt(): int
    {
        return (int) $this->attributes['lived_qt'];
    }

    public function getTotalArea(): float
    {
        return (float) $this->attributes['total_area'];
    }

    public function getPersonalAccount(): string
    {
        return (string) $this->attributes['personal_account'];
    }

    public function getAccountNumber(): string
    {
        return (string) $this->attributes['account_number'];
    }

    public function getApartmentId(): int
    {
        return (int) $this->attributes['apartment_id'];
    }

    public function getTariffId(): int
    {
        return (int) $this->attributes['tariff_id'];
    }

    public function getApartment(): \App\Models\Apartment
    {
        return $this->apartment;
    }

    public function getTariff(): \App\Models\Tariff
    {
        return $this->tariff;
    }
}
