<?php 

namespace App\Ddd\Domain\Repositories;

use Illuminate\Support\Collection;
use App\Dto\Payment\StoreDto;
use App\Dto\Payment\UpdateDto;
use App\Ddd\Domain\Entities\Payment;

interface PaymentsRepositoryInterface
{
    public function fetchAll(): Collection;
    public function fetchByUid(string $uid): Payment;
    public function add(StoreDto $dto): void;
    public function save(UpdateDto $dto): void;
}