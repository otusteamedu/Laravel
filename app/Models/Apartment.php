<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    use HasFactory;

    protected $fillable = ['owner', 'serial_number'];

    protected $casts = [
        'owner' => 'string',
        'serial_number' => 'integer',
    ];

    public function __toString(): string
    {
        return (string) $this->serial_number;
    }
}
