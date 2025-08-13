<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Product;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     description="Product model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Premium Headphones"),
 *     @OA\Property(property="alias", type="string", example="premium-headphones"),
 *     @OA\Property(property="text", type="string", nullable=true, example="High quality headphones with noise cancellation"),
 *     @OA\Property(property="image", type="string", nullable=true, example="headphones.jpg"),
 *     @OA\Property(
 *         property="images",
 *         type="array",
 *         nullable=true,
 *         @OA\Items(type="string", example="image1.jpg")
 *     ),
 *     @OA\Property(property="is_sale", type="boolean", example=true),
 *     @OA\Property(property="published", type="boolean", example=true),
 *     @OA\Property(property="order", type="integer", example=1),
 *     @OA\Property(property="price", type="number", format="float", example=199.99),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="categories",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Category")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="checked_at", type="string", format="date-time"),
 *         @OA\Property(property="version", type="string", example="1.0")
 *     )
 * )
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Product $product */
        $product = $this->resource;

        return [
            'id' => $product->id,
            'title' => $product->title,
            'alias' => $product->alias,
            'text' => $product->text,
            'image' => $product->image,
            'images' => $product->images ? json_decode($product->images, true) : null,
            'is_sale' => (bool)$product->is_sale,
            'published' => (bool)$product->published,
            'order' => $product->order,
            'price' => (float)$product->price,
            'created_at' => $product->created_at?->toIso8601String(),
            'updated_at' => $product->updated_at?->toIso8601String(),
            'categories' => $this->whenLoaded('categories', function () use ($product) {
                return CategoryResource::collection($product->categories);
            }),
        ];
    }

    /*
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'checked_at' => new \DateTime(),
            ]
        ];
    }*/
}
