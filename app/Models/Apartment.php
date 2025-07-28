<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $owner
 * @property string $serial_number
 */
class Apartment extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner',
        'serial_number'
    ];

    public function getOwner(): string
    {
        return $this->attributes['owner'];
    }

    public function getSerialNumber(): string
    {
        return $this->attributes['serial_number'];
    }
}
