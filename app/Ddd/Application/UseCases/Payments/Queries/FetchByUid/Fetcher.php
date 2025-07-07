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
            $payment->getId()->getValue(),
            $payment->getUid()->getValue(),
            $payment->getOrderId()->getValue(),
            $payment->getStatus()->getValue(),
            $payment->getAmount()->getValue(),
            $payment->getConfirmedAt()->getValue(),
            $payment->getCreatedAt(),
        );
        return $dto;
    }
}