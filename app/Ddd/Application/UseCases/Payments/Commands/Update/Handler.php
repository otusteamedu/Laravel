<?php

namespace App\Ddd\Application\UseCases\Payments\Commands\Update;

use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;
use App\Dto\Payment\UpdateDto;

class Handler
{
    public function __construct(private PaymentsRepositoryInterface $repository) {}

    public function handle(UpdateDto $dto): void
    {
        $this->repository->save($dto);
    }
}