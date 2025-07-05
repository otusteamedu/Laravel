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
        return $payments;
    }
}