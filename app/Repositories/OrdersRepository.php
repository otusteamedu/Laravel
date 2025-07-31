<?php

namespace App\Repositories;

use App\Dto\Admin\Order\StoreDto;
use App\Dto\Admin\Order\UpdateDto;
use App\Exceptions\OrderNotFoundException;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class OrdersRepository
{
    public function __construct(private UsersRepository $userRepository) {}

    /**
     * @return Collection<array-key, Order>
     */
    public function fetchAll(bool $warmup = false): Collection
    {
        $key = 'orders.all';
        $ttl = now()->addDay();
        $tag = 'order-list';

        if ($warmup) {
            $orders = Order::with('user')->get();
            Cache::tags($tag)->put($key, $orders, $ttl);
            return $orders;
        }

        $orders = Cache::tags($tag)->remember($key, $ttl, function () {
            return Order::with('user')->get();
        });
        
        return $orders;
    }

    /**
     * @return LengthAwarePaginator<array-key, Order>
     */
    public function fetchList($sort, string $direction, int $page, bool $warmup = false): LengthAwarePaginator
    {
        $key = 'orders.' . $sort . '.' . $direction . '.' . $page;
        $ttl = now()->addDay();
        $tag = 'order-list';

        $paginator = Cache::tags($tag)->get($key);

        if (!$paginator || $warmup) {
            $paginator = Order::with('user');

            if ($sort == 'user') {
                $paginator = $paginator->orderBy(User::select('name')->whereColumn('users.id', 'orders.user_id'), $direction);
            } else {
                $paginator = $paginator->orderBy($sort, $direction);
            }
            
            $perPage = config('custom.perPageAdmin');
            $paginator = $paginator->paginate($perPage)->withQueryString();
            Cache::tags($tag)->put($key, $paginator, $ttl);
            return $paginator;
        }

        return $paginator;
    }

    /**
     * @return Collection<array-key, Order>
     */
    public function fetchByUserEmail(string $email, bool $warmup = false): Collection
    {
        $key = 'order.email.' . $email;
        $ttl = now()->addDay();
        $tag = 'order-list';

        if ($warmup) {
            $orders = Order::with(['products'])->whereHas('user', function($query) use ($email) {
                $query->where('email', $email);
            })->get();
            Cache::tags($tag)->put($key, $orders, $ttl);
            return $orders;
        }

        $orders = Cache::tags($tag)->remember($key, $ttl, function () use($email) {
            return Order::with(['products'])->whereHas('user', function($query) use ($email) {
                $query->where('email', $email);
            })->get();
        });

        return $orders;
    }

    /**
     * @return Order
     */
    public function find(int $orderId, bool $warmup = false): Order
    {
        $key = 'order.' . $orderId;
        $ttl = now()->addDay();

        if ($warmup) {
            $order = Order::with(['user', 'products'])->find($orderId);
            if ($order) {
                Cache::put($key, $order, $ttl);
            }
            return $order;
        }

        $order = Cache::remember($key, $ttl, function () use($orderId) {
            return Order::with(['user', 'products'])->find($orderId);
        });

        if (!$order) {
            throw new OrderNotFoundException();
        }

        return $order;
    }

    /**
     * @return int
     */
    public function count(bool $warmup = false): int
    {
        $key = 'orders.count';
        $ttl = now()->addDay();
        
        if ($warmup) {
            $count = Order::count();
            Cache::put($key, $count, $ttl);
            return $count;
        }
        
        $count = Cache::remember($key, $ttl, function () {
            return Order::count();
        });

        return $count;
    }

    /**
     * @return Order
     */
    public function add(StoreDto $storeDto): Order
    {
        $order = new Order();
        $order->user_id = $storeDto->user_id;
        $order->status = 1;
        $order->save();

        Cache::tags('order-list')->flush();
        Cache::forget('orders.count');

        return $order;
    }

    /**
     * @return Order
     */
    public function save(UpdateDto $updateDto, ?int $status = null): Order
    {
        $order = Order::find($updateDto->id);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        $order->user_id = $updateDto->user_id;
        if (!empty($status)) {
            $order->status = $status;
        }
        $order->save();

        Cache::forget('order.' . $updateDto->id);
        Cache::tags('order-list')->flush();

        return $order;
    }

    public function saveStatus(int $id, int $status): Order
    {
        $order = Order::find($id);

        if (!$order) {
            throw new OrderNotFoundException();
        }

        $order->status = $status;
        $order->save();

        Cache::forget('order.' . $id);
        Cache::tags('order-list')->flush();

        return $order;
    }

    public function delete(int $orderId): void
    {
        $order = Order::find($orderId);

        if (!$order) {
            throw new OrderNotFoundException();
        }
        
        $order->delete();

        Cache::forget('order.' . $orderId);
        Cache::tags('order-list')->flush();
        Cache::forget('orders.count');
    }

    public function warmupCache(): void
    {
        $orders = $this->fetchAll(true);

        $perPage = config('custom.perPageAdmin');
        $totalPage = ceil(Order::count() / $perPage);

        $sorts = config('custom.ordersSorts');
        $directions = config('custom.ordersDirections');
        $pages = range(1, $totalPage);
        foreach ($sorts as $sort) {
            foreach ($directions as $direction) {
                foreach ($pages as $page) {
                    $this->fetchList($sort, $direction, $page, true);
                }
            }
        }
        
        $ids = $orders->pluck('id')->toArray();
        foreach ($ids as $orderId) {
            $this->find($orderId, true);
        }

        $users = $this->userRepository->fetchAll();
        $emails = $users->pluck('email')->toArray();
        foreach ($emails as $email) {
            $this->fetchByUserEmail($email, true);
        } 

        $this->count(true);
    }
}