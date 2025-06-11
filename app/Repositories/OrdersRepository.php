<?php

namespace App\Repositories;

use App\Dto\Admin\Order\StoreDto;
use App\Dto\Admin\Order\UpdateDto;
use App\Exceptions\OrderNotFoundException;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrdersRepository
{
    const ORDERS_PER_PAGE = 10;

    /**
     * @return Collection<array-key, Order>
     */
    public function fetchAll(): Collection
    {
        return Order::with('user')->get();
    }

    /**
     * @return LengthAwarePaginator<array-key, Order>
     */
    public function fetchList(string $sort, string $direction): LengthAwarePaginator
    {
        $paginator = Order::with('user');

        if ($sort == 'user') {
            $paginator = $paginator->orderBy(User::select('name')->whereColumn('users.id', 'orders.user_id'), $direction);
        } else {
            $paginator = $paginator->orderBy($sort, $direction);
        }
        
        $paginator = $paginator->paginate(self::ORDERS_PER_PAGE)->withQueryString();
        return $paginator;
    }

    /**
     * @return Order
     */
    public function find(int $orderId): Order
    {
        $order = Order::with(['user', 'products'])->find($orderId);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        return $order;
    }

    /**
     * @return Order
     */
    public function add(StoreDto $storeDto): Order
    {
        $order = new Order();
        $order->user_id = $storeDto->user_id;
        $order->save();

        return $order;
    }

    /**
     * @return Order
     */
    public function save(UpdateDto $updateDto): Order
    {
        $order = Order::find($updateDto->id);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        $order->user_id = $updateDto->user_id;
        $order->save();

        return $order;
    }

    public function delete(int $orderId): void
    {
        $order = Order::find($orderId);

        if (!$order) {
            throw new OrderNotFoundException();
        }
        
        $order->delete();
    }
}