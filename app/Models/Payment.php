<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Payment model.
 *
 * @property int $id
 * @property string $uid
 * @property int $order_id 
 * @property string $status 
 * @property int $amount 
 * @property string $confirmed_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Payment extends Model
{
    protected $guarded = [];
    protected $table = 'payments';

    public function getId(): int
    {
        return $this->id;
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getOrderId(): int
    {
        return $this->order_id;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getConfirmedAt(): ?string
    {
        return $this->confirmed_at;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updated_at;
    }
}
