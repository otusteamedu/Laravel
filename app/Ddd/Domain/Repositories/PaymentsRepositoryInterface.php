<?php 

namespace App\Ddd\Domain\Repositories;

use Illuminate\Support\Collection;
use App\Ddd\Domain\Entities\Payment;

interface PaymentsRepositoryInterface
{
    /**
     * @return Collection<int, Payment>
     */
    public function fetchAll(): Collection;
    public function fetchByUid(string $uid): Payment;
    public function add(Payment $payment): void;
    public function save(Payment $payment): void;
}