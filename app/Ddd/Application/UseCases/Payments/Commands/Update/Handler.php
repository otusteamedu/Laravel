<?php

namespace App\Ddd\Application\UseCases\Payments\Commands\Update;

use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;
use App\Ddd\Domain\ValueObjects\Status;
use App\Exceptions\PaymentAmountNotCorrectException;

class Handler
{
    public function __construct(private PaymentsRepositoryInterface $repository) {}

    public function handle(Dto $dto): void
    {
        $payment = $this->repository->fetchByUid($dto->uid);
        if ($payment->getAmount()->toInt() !== $dto->amount) {
            throw new PaymentAmountNotCorrectException();
        }

        if ($dto->status == Status::Succeeded->value) {
            $payment->confirm(now());
        }

        if ($dto->status == Status::Canceled->value) {
            $payment->cancel();
        }

        $this->repository->save($payment);
    }
}