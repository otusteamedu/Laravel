<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = ['owner', 'serial_number'];

    public function __toString()
    {
        return (string) $this->serialNumber;
    }
}