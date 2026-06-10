<?php
namespace App\Http\Controllers;

use Src\Orders\Application\Commands\PayOrderCommand;
use Src\Orders\Application\Handlers\GetOrderHandler;
use Src\Orders\Application\Handlers\PayOrderHandler;
use Src\Orders\Application\Queries\GetOrderQuery;

class OrderController
{
    public function pay(
        string $id,
        PayOrderHandler $handler
    ) {
        //Использования команд делает понятными намерения пользователя
        //Мы будет понимать что это. Оплата, возврат или отмена
        $handler->handle(
            new PayOrderCommand($id)
        );

        return response()->json([
            'success' => true
        ]);
    }

    public function show(
        string $id,
        GetOrderHandler $handler
    ) {
        return response()->json(
            $handler->handle(
                new GetOrderQuery($id)
            )
        );
    }
}