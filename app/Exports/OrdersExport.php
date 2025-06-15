<?php
namespace App\Exports;

use App\Services\OrdersService;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class OrdersExport implements FromView
{
    public function __construct(private OrdersService $service) 
    {}

    public function view(): View
    {
        $orders = $this->service->getAll();
        return view('exports.orders', compact('orders'));
    }
}