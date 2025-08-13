<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @OA\Schema(
 *     schema="CategoryCollection",
 *     type="object",
 *     title="Paginated Category List",
 *     description="Collection of categories with pagination metadata",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         description="Array of category items",
 *         @OA\Items(ref="#/components/schemas/Category")
 *     ),
 *     @OA\Property(
 *         property="links",
 *         type="object",
 *         description="Pagination links",
 *         @OA\Property(
 *             property="first",
 *             type="string",
 *             format="url",
 *             example="http://api.example.com/v1/categories?page=1"
 *         ),
 *         @OA\Property(
 *             property="last",
 *             type="string",
 *             format="url",
 *             example="http://api.example.com/v1/categories?page=10"
 *         ),
 *         @OA\Property(
 *             property="prev",
 *             type="string",
 *             format="url",
 *             nullable=true,
 *             example="http://api.example.com/v1/categories?page=1"
 *         ),
 *         @OA\Property(
 *             property="next",
 *             type="string",
 *             format="url",
 *             nullable=true,
 *             example="http://api.example.com/v1/categories?page=3"
 *         ),
 *         @OA\Property(
 *             property="self",
 *             type="string",
 *             format="url",
 *             example="http://api.example.com/v1/categories?page=2"
 *         )
 *     ),
 *     @OA\Property(
 *         property="meta",
 *         type="object",
 *         description="Pagination metadata",
 *         @OA\Property(
 *             property="current_page",
 *             type="integer",
 *             example=2
 *         ),
 *         @OA\Property(
 *             property="from",
 *             type="integer",
 *             example=16
 *         ),
 *         @OA\Property(
 *             property="last_page",
 *             type="integer",
 *             example=10
 *         ),
 *         @OA\Property(
 *             property="path",
 *             type="string",
 *             format="url",
 *             example="http://api.example.com/v1/categories"
 *         ),
 *         @OA\Property(
 *             property="per_page",
 *             type="integer",
 *             example=15
 *         ),
 *         @OA\Property(
 *             property="to",
 *             type="integer",
 *             example=30
 *         ),
 *         @OA\Property(
 *             property="total",
 *             type="integer",
 *             example=150
 *         ),
 *         @OA\Property(
 *             property="version",
 *             type="string",
 *             example="1.0.0"
 *         ),
 *         @OA\Property(
 *             property="timestamp",
 *             type="string",
 *             format="date-time",
 *             example="2023-05-15T12:34:56Z"
 *         )
 *     )
 * )
 */
class CategoryCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = 'data';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'data' => $this->collection,
            'links' => [
                'first' => $this->url(1),
                'last' => $this->url($this->lastPage()),
                'prev' => $this->previousPageUrl(),
                'next' => $this->nextPageUrl(),
                'self' => $request->fullUrl(),
            ],
            'meta' => [
                'current_page' => $this->currentPage(),
                'from' => $this->firstItem(),
                'last_page' => $this->lastPage(),
                'path' => $this->path(),
                'per_page' => $this->perPage(),
                'to' => $this->lastItem(),
                'total' => $this->total()
            ]
        ];
    }

}
