<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property \App\Models\Product $resource
 */
class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = $this->resource;
        return [
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'paid_price' => $product->pivot->paid_price,
            'count' => $product->pivot->count,
        ];
    }
}
