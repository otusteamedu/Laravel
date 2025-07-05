<?php 

namespace App\Ddd\Domain\Repositories;

use Illuminate\Support\Collection;

interface PaymentsRepositoryInterface
{
    public function fetchAll(): Collection;
}