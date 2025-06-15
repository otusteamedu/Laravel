<?php

namespace App\Exports;

use App\Services\ProductsService;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ProductsExport implements FromView
{
    public function __construct(private ProductsService $service) 
    {}

    public function view(): View
    {
        $products = $this->service->getAll();
        return view('exports.products', compact('products'));
    }
}
