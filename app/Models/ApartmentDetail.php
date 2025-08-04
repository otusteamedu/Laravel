<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="ApartmentDetail",
 *     type="object",
 *     title="Apartment Detail",
 *     required={"registred_qt","lived_qt","total_area","personal_account","account_number","apartment_id","tariff_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="registred_qt", type="integer", example=3, description="Количество зарегистрированных"),
 *     @OA\Property(property="lived_qt", type="integer", example=2, description="Количество проживающих"),
 *     @OA\Property(property="total_area", type="number", format="float", example=45.7, description="Общая площадь"),
 *     @OA\Property(property="personal_account", type="string", example="12345678", description="Лицевой счет"),
 *     @OA\Property(property="account_number", type="string", example="ACC-98765", description="Номер счета"),
 *     @OA\Property(property="apartment_id", type="integer", example=10, description="ID квартиры"),
 *     @OA\Property(property="tariff_id", type="integer", example=5, description="ID тарифа")
 * )
 *
 * @OA\Schema(
 *     schema="ApartmentDetailCreateRequest",
 *     type="object",
 *     required={"registred_qt","lived_qt","total_area","personal_account","account_number","apartment_id","tariff_id"},
 *     @OA\Property(property="registred_qt", type="integer", example=3),
 *     @OA\Property(property="lived_qt", type="integer", example=2),
 *     @OA\Property(property="total_area", type="number", format="float", example=45.7),
 *     @OA\Property(property="personal_account", type="string", example="12345678"),
 *     @OA\Property(property="account_number", type="string", example="ACC-98765"),
 *     @OA\Property(property="apartment_id", type="integer", example=10),
 *     @OA\Property(property="tariff_id", type="integer", example=5)
 * )
 *
 * @OA\Schema(
 *     schema="ApartmentDetailUpdateRequest",
 *     type="object",
 *     @OA\Property(property="registred_qt", type="integer", example=4),
 *     @OA\Property(property="lived_qt", type="integer", example=3),
 *     @OA\Property(property="total_area", type="number", format="float", example=48.2),
 *     @OA\Property(property="personal_account", type="string", example="654321"),
 *     @OA\Property(property="account_number", type="string", example="ACC-12345"),
 *     @OA\Property(property="apartment_id", type="integer", example=12),
 *     @OA\Property(property="tariff_id", type="integer", example=6)
 * )
 *
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
