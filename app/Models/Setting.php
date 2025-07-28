<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $month_name
 * @property int $month_to_pay
 * @property int $month_to_date
 * @property string $bill
 * @property string $pay_up_to
 */
class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'month_name',
        'month_to_pay',
        'month_to_date',
        'bill',
        'pay_up_to',
    ];

    public function getMonthName(): string
    {
        return (string) $this->attributes['month_name'];
    }

    public function getMonthToPay(): int
    {
        return (int) $this->attributes['month_to_pay'];
    }

    public function getMonthToDate(): int
    {
        return (int) $this->attributes['month_to_date'];
    }

    public function getBill(): string
    {
        return (string) $this->attributes['bill'];
    }

    public function getPayUpTo(): string
    {
        return (string) $this->attributes['pay_up_to'];
    }
}
