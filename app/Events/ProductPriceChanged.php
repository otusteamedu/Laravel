<?php

namespace App\Events;

use App\DTO\ProductPriceData;
use App\Models\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductPriceChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ProductPriceData $productPriceData;

    public function __construct(ProductPriceData $priceData)
    {
       $this->productPriceData = $priceData;
    }
}
