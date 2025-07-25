<?php

namespace App\Ddd\Application\UseCases\Payments\Commands\Pay;

use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;


class Handler
{
    public function __construct(private PaymentsRepositoryInterface $repository) {}

    public function handle(Dto $dto): void
    {
        $payment = $this->repository->fetchByUid($dto->uid);
        $payment->confirm($dto->amount);
        $this->repository->save($payment);
    }
}