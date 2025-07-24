<?php

namespace App\Ddd\Domain\Entities;

use App\Ddd\Domain\ValueObjects\Amount;
use App\Ddd\Domain\ValueObjects\Id;
use App\Ddd\Domain\ValueObjects\Status;
use App\Ddd\Domain\ValueObjects\Uid;
use App\Exceptions\IllegalStateTransitionException;
use Illuminate\Support\Carbon;

class Payment
{
    public function __construct(
        private Uid $uid, 
        private Id $order_id, 
        private Status $status,
        private Amount $amount,
        private ?Id $id = null, 
        private ?Carbon $confirmed_at = null,
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

    public function getConfirmedAt(): ?Carbon
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

    public function confirm(Carbon $date): void 
    {
        if (!$this->status = Status::Pending) {
            throw new IllegalStateTransitionException();
        }
        $this->status = Status::Succeeded;
        $this->confirmed_at = $date;
    }

    public function cancel(): void 
    {
        if (!$this->status = Status::Canceled) {
            throw new IllegalStateTransitionException();
        }
        $this->status = Status::Canceled;
    }
}
