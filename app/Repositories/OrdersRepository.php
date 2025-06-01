<?php

namespace App\Repositories;

use App\Dto\Order\StoreDto;
use App\Dto\Order\UpdateDto;
use App\Exceptions\OrderNotFoundException;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderProduct;

class OrdersRepository
{
    const ORDERS_PER_PAGE = 10;

    public function fetchAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Order::with('user')->get();
    }

    public function fetchList(string $sort, string $direction): \Illuminate\Pagination\LengthAwarePaginator
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

    public function find(int $orderId): Order
    {
        $order = Order::with(['user', 'products'])->find($orderId);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        return $order;
    }

    public function add(StoreDto $storeDto): Order
    {
        $order = new Order();
        $order->user_id = $storeDto->user_id;
        $order->save();

        return $order;
    }

    public function addProducts(Order $order, array $items): void
    {
        $order->products()->sync($items);
    }

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

    public function deleteProducts(int $orderId): void
    {
        OrderProduct::where('order_id', $orderId)->delete();
    }
}