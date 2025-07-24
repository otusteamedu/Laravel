<?php

namespace App\Ddd\Application\UseCases\Payments\Queries\FetchAll;

use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;
use Illuminate\Support\Collection;

class Fetcher
{
    public function __construct(private PaymentsRepositoryInterface $repository) {}

    /**
     * @return Collection<int, Dto>
     */
    public function fetch(): Collection
    {
        $payments = $this->repository->fetchAll();
        $dto = [];
        foreach ($payments as $payment) {
            $dto[] = new Dto(
                $payment->getId()->toInt(),
                $payment->getUid()->toString(),
                $payment->getOrderId()->toInt(),
                $payment->getStatus()->toString(),
                $payment->getAmount()->toInt(),
                $payment->getConfirmedAt()->toString(),
                $payment->getCreatedAt()
            );
        }
        $coll = collect($dto);
        return $coll;
    }
}