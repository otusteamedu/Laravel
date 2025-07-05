<?php

namespace App\Ddd\Application\UseCases\Payments\Queries\FetchByUid;

use App\Ddd\Domain\Entities\Payment;
use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;

class Fetcher
{
    public function __construct(private PaymentsRepositoryInterface $repository) {}

    public function fetch(string $uid): Payment
    {
        $payment = $this->repository->fetchByUid($uid);
        return $payment;
    }
}