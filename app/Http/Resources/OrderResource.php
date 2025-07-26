<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $order = $this->resource;
        return [
            'id' => $order->getId(),
            'user_id' => $order->getUserId(),
            'status' => $order->getStatus(),
            'total' => $order->getTotal(),
            'created_at' => $order->getCreatedAt()->format('d.m.Y H:i'),
            'updated_at' => $order->getUpdatedAt()->format('d.m.Y H:i'),
            'products' => OrderProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
