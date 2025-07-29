<?php

namespace App\Domain\Apartment;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domain\Apartment\ValueObjects\Owner;
use App\Domain\Apartment\ValueObjects\SerialNumber;
use App\Models\ApartmentDetail;
use App\Models\ApartmentFee;

/**
 * @property string $owner
 * @property int $serial_number
 */

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = ['owner', 'serial_number'];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->owner = (string) new Owner($model->owner);
            $model->serial_number = (new SerialNumber((int) $model->serial_number))->getValue();
        });
    }

    public function getOwner(): Owner
    {
        return new Owner($this->attributes['owner']);
    }

    public function getSerialNumber(): SerialNumber
    {
        return new SerialNumber((int) $this->attributes['serial_number']);
    }

    public function changeOwner(Owner $newOwner): void
    {
        if (empty((string)$newOwner)) {
            throw new \InvalidArgumentException('Не может быть пусто');
        }
        $this->attributes['owner'] = (string) $newOwner;
    }

    public function details()
    {
        return $this->hasMany(ApartmentDetail::class);
    }

    public function fees()
    {
        return $this->hasMany(ApartmentFee::class);
    }
}
