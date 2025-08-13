<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Category;

/**
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     title="Category",
 *     description="Product category model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Electronics"),
 *     @OA\Property(property="alias", type="string", example="electronics"),
 *     @OA\Property(property="text", type="string", nullable=true, example="All electronic devices"),
 *     @OA\Property(property="published", type="boolean", example=true),
 *     @OA\Property(property="order", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="products",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Product")
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         @OA\Property(property="checked_at", type="string", format="date-time"),
 *         @OA\Property(property="version", type="string", example="1.0")
 *     )
 * )
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Category $category */
        $category = $this->resource;

        return [
            'id' => $category->id,
            'title' => $category->title,
            'alias' => $category->alias,
            'text' => $category->text,
            'published' => (bool)$category->published,
            'order' => $category->order,
            'created_at' => $category->created_at?->toIso8601String(),
            'updated_at' => $category->updated_at?->toIso8601String(),
            'products' => $this->whenLoaded('products', function () {
                return ProductResource::collection($this->products);
            }),
        ];
    }
}
