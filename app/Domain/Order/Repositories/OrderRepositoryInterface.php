<?php

namespace App\Domain\Order\Repositories;

use App\Domain\Order\Model\Order;

interface OrderRepositoryInterface
{
    public function findById(int $id): ?Order;

    /**
     * @return Order[]
     */
    public function findByUserId(int $userId): array;

    public function findByStatus(string $status): array;

    public function save(Order $order): Order;

    public function delete(int $orderId): void;

    /**
     * @param array $criteria
     * @return Order[]
     */
    public function findByCriteria(array $criteria): array;

    /**
     * @param int $page
     * @param int $perPage
     * @param array $criteria
     * @return array{data: Order[], total: int, current_page: int, per_page: int, last_page: int}
     */
    public function paginate(int $page = 1, int $perPage = 15, array $criteria = []): array;
}
