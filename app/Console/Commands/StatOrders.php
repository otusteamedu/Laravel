<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OrdersService;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use function Laravel\Prompts\table;
use function Laravel\Prompts\info;

class StatOrders extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stat:orders {email} {--details}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show orders statistics';

    /**
     * Execute the console command.
     */
    public function handle(
        OrdersService $service,
    )
    {
        $email = $this->argument('email');
        $details = $this->option('details');

        $orders = $service->getByUserEmail($email);
        $count = $orders->count();

        $cost = 0;
        foreach ($orders as $order) {
            $cost += $order->getTotal();
        }

        info('Total count: ' . $count);
        info('Total cost: ' . $cost);

        if ($details) {
            $data = [];
            foreach ($orders as $order) {
                $orderId = $order->getId();
                $createdAt = $order->getCreatedAt()->format('d.m.Y H:i');
                $cost = $order->getTotal();
                $quantity = $order->getProducts()->count();
                $data[] = [$orderId, $createdAt, $quantity, $cost];
            }
            $headers = ['Id', 'CreatedAt', 'Quantity of products', 'Total cost'];
            table($headers, $data);
        }
    }
}
