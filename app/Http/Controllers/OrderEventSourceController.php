<?php
namespace App\Http\Controllers;

use Src\OrdersEventSource\Application\Handlers\PayOrderHandler;
use Src\OrdersEventSource\Application\Commands\PayOrderCommand;

class OrderEventSourceController extends Controller
{
    public function __construct(
        private PayOrderHandler $handler
    ) {}

    public function pay(int $orderId)
    {
        $command = new PayOrderCommand($orderId);

        $this->handler->handle($command);

        return response()->json(['status' => 'ok']);
    }

}