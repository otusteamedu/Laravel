<?php

namespace App\Ddd\Application\UseCases\Payments\Commands\Store;

use App\Ddd\Domain\Entities\Payment;
use App\Ddd\Domain\Repositories\PaymentsRepositoryInterface;
use App\Ddd\Domain\ValueObjects\Amount;
use App\Ddd\Domain\ValueObjects\Id;
use App\Ddd\Domain\ValueObjects\Status;
use App\Ddd\Domain\ValueObjects\Uid;
use App\Repositories\OrdersRepository;
use YooKassa\Client;
use App\Dto\Admin\Order\UpdateDto;

class Handler
{
    public function __construct(
        private PaymentsRepositoryInterface $repository,
        private OrdersRepository $ordersRepository,
        private Client $yooClient,
        private string $appUrl
    ) {}

    public function handle(int $orderId): string
    {
        $order = $this->ordersRepository->find($orderId);
        $amount = $order->getTotal();
        $userId = auth()->id();

        $url = $this->appUrl . "/history/$userId";
        $data = [
            'amount' => ['value' => $amount, 'currency' => 'RUB'],
            'confirmation' => ['type' => 'redirect', 'return_url' => $url],
            'capture' => true,
        ];
        $response = $this->yooClient->createPayment($data, uniqid('', true));

        $uid = $response->getId();
        $confirmationUrl = $response->getConfirmation()->getConfirmationUrl();

        $payment = new Payment(new Uid($uid), new Id($orderId), Status::Pending, new Amount($amount));
        $this->repository->add($payment);

        $dto = new UpdateDto($orderId, $order->getUser()->id);
        $status = 2;
        $this->ordersRepository->save($dto, $status);

        return $confirmationUrl;
    }
}