<?php

namespace App\Repositories;

use App\Dto\User\PaymentDto;
use App\Models\Payment;

class PaymentsRepository
{
    public function add(PaymentDto $dto): void
    {
        $payment = new Payment();
        $payment->uid = $dto->uid;
        $payment->order_id = $dto->orderId;
        $payment->status = $dto->status;
        $payment->amount = $dto->amount;
        $payment->save();
    }
}