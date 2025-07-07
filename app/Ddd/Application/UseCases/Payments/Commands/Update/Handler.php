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
        if ($payment->getAmount()->getValue() !== $dto->amount) {
            throw new PaymentAmountNotCorrectException();
        }
        $payment->changeStatus(new Status($dto->status));
        $this->repository->save($payment);
    }
}