<?php

namespace App\Ddd\Domain\Entities;

use Illuminate\Support\Carbon;

/**
 * Payment model.
 *
 * @property int $id
 * @property string $uid
 * @property int $order_id 
 * @property string $status 
 * @property int $amount 
 * @property ?string $confirmed_at
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 */
class Payment
{
    public function __construct(
        private int $id, 
        private string $uid, 
        private int $order_id, 
        private string $status,
        private int $amount,
        private ?string $confirmed_at,
        private ?Carbon $created_at,
        private ?Carbon $updated_at
    ) {}

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
