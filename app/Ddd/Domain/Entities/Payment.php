<?php

namespace App\Ddd\Domain\Entities;

use App\Ddd\Domain\ValueObjects\Amount;
use App\Ddd\Domain\ValueObjects\Id;
use App\Ddd\Domain\ValueObjects\Status;
use App\Ddd\Domain\ValueObjects\StringDate;
use App\Ddd\Domain\ValueObjects\Uid;
use Illuminate\Support\Carbon;

class Payment
{
    public function __construct(
        private Uid $uid, 
        private Id $order_id, 
        private Status $status,
        private Amount $amount,
        private ?Id $id = null, 
        private ?StringDate $confirmed_at = null,
        private ?Carbon $created_at = null,
        private ?Carbon $updated_at = null
    ) {}

    public function getId(): ?Id
    {
        return $this->id;
    }

    public function getUid(): Uid
    {
        return $this->uid;
    }

    public function getOrderId(): Id
    {
        return $this->order_id;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getConfirmedAt(): ?StringDate
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

    public function changeStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function setConfirmedAt(StringDate $date): void
    {
        $this->confirmed_at = $date;
    }
}
