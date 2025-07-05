<?php 

namespace App\Ddd\Domain\Repositories;

use Illuminate\Support\Collection;
use App\Dto\Payment\StoreDto;

interface PaymentsRepositoryInterface
{
    public function fetchAll(): Collection;
    public function add(StoreDto $dto): void;
}