<?php
namespace App\Services;

use App\Dto\Admin\Order\StoreDto;
use App\Dto\Admin\Order\UpdateDto;
use App\Dto\Order\StatusDto;
use App\Dto\Payment\StoreDto as PaymentDto;
use App\Models\Order;
use App\Repositories\OrdersRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\PaymentsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use YooKassa\Client;
use Illuminate\Support\Facades\Log;

class OrdersService
{
    public function __construct(
        private OrdersRepository $repository,
        private ProductsRepository $productsRepository,
        private PaymentsRepository $paymentsRepository,
    ) {}

    /**
     * @return Collection<array-key, Order>
     */
    public function getAll(): Collection
    {
        return $this->repository->fetchAll();
    }

    /**
     * @return LengthAwarePaginator<array-key, Order>
     */
    public function getList(string $sort, string $direction, int $page): LengthAwarePaginator
    {
        return $this->repository->fetchList($sort, $direction, $page);
    }

    /**
     * @return Collection<array-key, Order>
     */
    public function getByUserEmail(string $email): Collection
    {
        return $this->repository->fetchByUserEmail($email);
    }

    /**
     * @return Order
     */
    public function getById(int $orderId): Order
    {
        return $this->repository->find($orderId);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->repository->count();
    }
    
    public function add(StoreDto $storeDto, array $product_ids, array $counts): Order
    {
        $order = $this->repository->add($storeDto);
        $products = $this->productsRepository->findByIds($product_ids);
        $countsByProductId = array_combine($product_ids, $counts);

        $items = [];
        foreach ($products as $product) {
            $id = $product->getId();
            $items[$id] = [
                'count' => $countsByProductId[$id], 
                'paid_price' => $product->getPrice(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $order->products()->sync($items);

        return $order;
    }

    public function update(UpdateDto $updateDto, array $product_ids, array $counts): void
    {
        $order = $this->repository->save($updateDto);
        $products = $this->productsRepository->findByIds($product_ids);
        $countsByProductId = array_combine($product_ids, $counts);

        $items = [];
        foreach ($products as $product) {
            $id = $product->getId();
            $count = $countsByProductId[$id];
            if ($count > 0) {
                $items[$id] = ['count' => $count, 'paid_price' => $product->getPrice()];
            }
        }

        $order->products()->sync($items);
    }

    public function delete(int $orderId): void
    {
        $order = $this->repository->find($orderId);
        $products = $order->getProducts();
        foreach ($products as $product) {
            $product->stock += $product->pivot->count;
            $product->save();
        }

        $order->products()->detach();
        $this->repository->delete($orderId);
    }

    public function pay(int $orderId): string
    {
        $order = $this->getById($orderId);
        $amount = $order->getTotal();
        $userId = auth()->id();

        $appUrl = config('app.url');
        $yooId = (string) config('custom.yookassaId');
        $yooSecret = (string) config('custom.yookassaSecret');

        $client = new Client();
        $client->setAuth($yooId, $yooSecret);
        $url = $appUrl . "/history/$userId";
        $data = [
            'amount' => ['value' => $amount, 'currency' => 'RUB'],
            'confirmation' => ['type' => 'redirect', 'return_url' => $url],
            'capture' => true,
        ];
        $response = $client->createPayment($data, uniqid('', true));

        $uid = $response->getId();
        $confirmationUrl = $response->getConfirmation()->getConfirmationUrl();

        $dto = new PaymentDto($uid, $orderId, 'pending', $amount);
        $this->paymentsRepository->add($dto);

        $dto = new UpdateDto($orderId, $order->getUser()->id);
        $status = 2;
        $this->repository->save($dto, $status);

        return $confirmationUrl;
    }

    public function updateStatus(StatusDto $dto): void
    {
        $status = $dto->status == 'succeeded' ? 3 : 4;
        $this->repository->saveStatus($dto->id, $status);
    }
}