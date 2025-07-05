<?php

namespace App\Ddd\Application\UseCases\Payments\Commands\Store;

use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;
use App\Dto\Payment\StoreDto as PaymentDto;
use App\Repositories\OrdersRepository;
use YooKassa\Client;
use App\Dto\Admin\Order\UpdateDto;

class Handler
{
    public function __construct(
        private PaymentsRepositoryInterface $repository,
        private OrdersRepository $ordersRepository
    ) {}

    public function handle(int $orderId): string
    {
        $order = $this->ordersRepository->find($orderId);
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
        $this->repository->add($dto);

        $dto = new UpdateDto($orderId, $order->getUser()->id);
        $status = 2;
        $this->ordersRepository->save($dto, $status);

        return $confirmationUrl;
    }
}