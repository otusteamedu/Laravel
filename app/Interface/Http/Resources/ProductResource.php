<?php

namespace App\Interface\Http\Resources;

use App\Domain\Product\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'alias' => $product->getAlias(),
            'text' => $product->getText(),
            'image' => $product->getImage(),
            'images' => $product->getImages(),
            'is_sale' => (bool)$product->getIsSale(),
            'published' => (bool)$product->getPublished(),
            'order' => $product->getOrder(),
            'price' => $product->getPrice(),
            'user_id' => $product->getUserId(),
            'categories' => $product->getCategoryIds(),
            'created_at' => null,
            'updated_at' => null,
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
