<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ApartmentFee;
use App\Models\ApartmentDetail;

class ApartmentModel extends Model
{
    protected $table = 'apartments';

    protected $fillable = ['owner', 'serial_number'];

    public function details()
    {
        return $this->hasMany(ApartmentDetail::class, 'apartment_id');
    }

    public function fees()
    {
        return $this->hasMany(ApartmentFee::class, 'apartment_id'); 
    }
}
