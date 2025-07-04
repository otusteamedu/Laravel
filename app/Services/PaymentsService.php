<?php
namespace App\Services;

use App\Dto\Payment\UpdateDto;
use App\Repositories\PaymentsRepository;

class PaymentsService
{
    public function __construct(
        private PaymentsRepository $repository,
    ) {}

    public function update(UpdateDto $updateDto): void
    {
        $this->repository->save($updateDto);
    }
}