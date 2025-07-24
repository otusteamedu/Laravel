<?php

namespace App\Ddd\Application\UseCases\Payments\Queries\FetchByUid;

use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;

class Fetcher
{
    public function __construct(private PaymentsRepositoryInterface $repository) {}

    public function fetch(string $uid): Dto
    {
        $payment = $this->repository->fetchByUid($uid);
        $dto = new Dto(
            $payment->getId()->toInt(),
            $payment->getUid()->toString(),
            $payment->getOrderId()->toInt(),
            $payment->getStatus()->value,
            $payment->getAmount()->toInt(),
            $payment->getConfirmedAt(),
            $payment->getCreatedAt()
        );
        return $dto;
    }
}