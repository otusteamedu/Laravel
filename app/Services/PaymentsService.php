<?php
namespace App\Services;

use App\Dto\Payment\UpdateDto;
use App\Models\Payment;
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

    public function getByUid(string $uid): Payment
    {
        return $this->repository->findByUid($uid);
    }
}