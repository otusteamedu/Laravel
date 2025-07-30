<?php

namespace App\Domain\Apartment;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Apartment\ValueObjects\Owner;
use App\Domain\Apartment\ValueObjects\SerialNumber;

class Apartment extends Model
{
    protected $fillable = ['owner', 'serial_number'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function create(Owner $owner, SerialNumber $serialNumber): self
    {
        $apartment = new self();
        $apartment->attributes['owner'] = $owner->toString();
        $apartment->attributes['serial_number'] = $serialNumber->toInt();
        return $apartment;
    }

    public function getOwner(): Owner
    {
        return new Owner($this->attributes['owner']);
    }

    public function getSerialNumber(): SerialNumber
    {
        return new SerialNumber((int) $this->attributes['serial_number']);
    }

    public function details()
    {
        return $this->hasMany(\App\Models\ApartmentDetail::class);
    }

    public function fees()
    {
        return $this->hasMany(\App\Models\ApartmentFee::class);
    }
}

