<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'month_name',
        'month_to_pay',
        'month_to_date',
        'bill',
        'pay_up_to'
    ];
}