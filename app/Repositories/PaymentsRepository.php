<?php

namespace App\Repositories;

use App\Dto\Payment\StoreDto;
use App\Dto\Payment\UpdateDto;
use App\Exceptions\PaymentAmountNotCorrectException;
use App\Exceptions\PaymentNotFoundException;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Collection;

class PaymentsRepository
{
    /**
     * @return Collection<array-key, Payment>
     */
    public function fetchAll(): Collection
    {
        $payments = Payment::all();
        return $payments;
    }

    public function add(StoreDto $dto): void
    {
        $payment = new Payment();
        $payment->uid = $dto->uid;
        $payment->order_id = $dto->orderId;
        $payment->status = $dto->status;
        $payment->amount = $dto->amount;
        $payment->save();
    }

     public function save(UpdateDto $updateDto): void
    {
        $payment = Payment::where(['uid' => $updateDto->uid])->first();

        if (!$payment) {
            throw new PaymentNotFoundException();
        }

        if ($payment->getAmount() != $updateDto->amount) {
            throw new PaymentAmountNotCorrectException();
        }

        $payment->status = $updateDto->status;
        if ($updateDto->status == 'succeeded') {
            $payment->confirmed_at = now();
        }
        $payment->save();
    }

    public function findByUid(string $uid): Payment
    {
        $payment = Payment::where(['uid' => $uid])->first();

        if (!$payment) {
            throw new PaymentNotFoundException();
        }

        return $payment;
    }
}