<?php

namespace App\Ddd\Application\UseCases\Payments\Queries\FetchAll;

use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;
use Illuminate\Support\Collection;

class Fetcher
{
    public function __construct(private PaymentsRepositoryInterface $repository) {}

    public function fetch(): Collection
    {
        $payments = $this->repository->fetchAll();
        $dto = [];
        foreach ($payments as $payment) {
            $dto[] = new Dto(
                $payment->getId()->getValue(),
                $payment->getUid()->getValue(),
                $payment->getOrderId()->getValue(),
                $payment->getStatus()->getValue(),
                $payment->getAmount()->getValue(),
                $payment->getConfirmedAt()->getValue(),
                $payment->getCreatedAt()->getValue(),
            );
        }
        $coll = collect($dto);
        return $coll;
    }
}