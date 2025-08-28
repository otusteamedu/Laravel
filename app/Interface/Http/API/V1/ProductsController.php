<?php

namespace App\Interface\Http\API\V1;

use App\Application\Services\ProductAppService;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 * name="Products",
 * description="API Endpoints for Product Management"
 * )
 *
 * @OA\Schema(
 * schema="ProductRequest",
 * title="Product Request",
 * description="Data structure for creating a new product",
 * @OA\Property(property="title", type="string", description="Product title", example="Premium Headphones"),
 * @OA\Property(property="text", type="string", description="Product description", nullable=true, example="High quality headphones with noise cancellation."),
 * @OA\Property(property="image", type="string", description="Main image URL for the product", nullable=true, example="image.jpg"),
 * @OA\Property(
 * property="images",
 * type="array",
 * description="Array of additional image URLs for the product",
 * nullable=true,
 * @OA\Items(type="string", example="image1.jpg")
 * ),
 * @OA\Property(property="is_sale", type="boolean", description="Indicates if the product is on sale", example=false),
 * @OA\Property(property="published", type="boolean", description="Publication status of the product", example=true),
 * @OA\Property(property="order", type="integer", description="Display order of the product", example=1),
 * @OA\Property(property="price", type="number", format="float", description="Price of the product", example=199.99),
 * @OA\Property(property="user_id", type="integer", description="ID of the user who created the product", example=1),
 * @OA\Property(
 * property="category_ids",
 * type="array",
 * description="An array of category IDs the product belongs to",
 * @OA\Items(type="integer", example=1)
 * ),
 * )
 */
class ProductsController extends Controller
{
    public function __construct(
        private ProductAppService $productAppService
    ) {}

    /**
     * @OA\Get(
     * path="/api/v1/products",
     * operationId="getProductsList",
     * tags={"Products"},
     * summary="Get list of products",
     * description="Returns paginated list of products with filtering and sorting options",
     * @OA\Parameter(
     * name="page",
     * in="query",
     * description="Page number",
     * required=false,
     * @OA\Schema(type="integer", default=1)
     * ),
     * @OA\Parameter(
     * name="per_page",
     * in="query",
     * description="Items per page",
     * required=false,
     * @OA\Schema(type="integer", default=15)
     * ),
     * @OA\Parameter(
     * name="category_id",
     * in="query",
     * description="Filter by category ID",
     * required=false,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     * name="on_sale",
     * in="query",
     * description="Filter by sale status",
     * required=false,
     * @OA\Schema(type="boolean")
     * ),
     * @OA\Parameter(
     * name="sort_by",
     * in="query",
     * description="Sort by field (e.g., price, created_at)",
     * required=false,
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="sort_order",
     * in="query",
     * description="Sort order (asc or desc)",
     * required=false,
     * @OA\Schema(type="string", enum={"asc", "desc"})
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product"))
     * )
     * )
     * )
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'category_id' => 'sometimes|integer|exists:categories,id',
            'is_sale' => 'sometimes|boolean',
            'published' => 'sometimes|boolean',
            'sort' => 'sometimes|in:price,order,created_at,title',
            'direction' => 'sometimes|in:asc,desc',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $criteria = [
            'category_id' => $request->category_id,
            'is_sale' => $request->is_sale,
            'published' => $request->published,
            'sort' => $request->sort ?? 'order',
            'direction' => $request->direction ?? 'asc',
        ];

        $page = $request->page ?? 1;
        $perPage = $request->per_page ?? 15;

        $result = $this->productAppService->getProductsPaginated($page, $perPage, $criteria);

        return response()->json([
            'data' => array_map(fn($product) => $product->toArray(), $result['data']),
            'meta' => [
                'current_page' => $result['current_page'],
                'per_page' => $result['per_page'],
                'total' => $result['total'],
                'last_page' => $result['last_page'],
            ]
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/v1/products/{id}",
     * operationId="getProductById",
     * tags={"Products"},
     * summary="Get product by ID",
     * description="Returns a single product record",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="Product ID",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Product")
     * ),
     * @OA\Response(
     * response=404,
     * description="Product not found"
     * )
     * )
     */
    public function show($id)
    {
        $product = $this->productAppService->getProductById((int)$id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return new ProductResource($product);
    }

    /**
     * @OA\Post(
     * path="/api/v1/products",
     * operationId="createProduct",
     * tags={"Products"},
     * summary="Create new product",
     * description="Creates a new product record",
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Product created successfully",
     * @OA\JsonContent(ref="#/components/schemas/Product")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255|unique:products,alias',
            'text' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'string|max:255',
            'is_sale' => 'boolean',
            'published' => 'boolean',
            'order' => 'nullable|integer',
            'price' => 'required|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth('jwt')->user();


        try {
            $product = $this->productAppService->createProduct(
                title: $request->title,
                price: (float)$request->price,
                userId: $user->id,
                alias: $request->alias,
                text: $request->text,
                image: $request->image,
                images: $request->images ?? [],
                isSale: (bool)($request->is_sale ?? false),
                published: (bool)($request->published ?? true),
                order: (int)($request->order ?? 0),
                categoryIds: $request->categories ?? []
            );

            return (new ProductResource($product))
                ->response()
                ->setStatusCode(201);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Put(
     * path="/api/v1/products/{id}",
     * operationId="updateProduct",
     * tags={"Products"},
     * summary="Update existing product",
     * description="Updates an existing product record by ID",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="Product ID",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Product updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/Product")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * ),
     * @OA\Response(
     * response=404,
     * description="Product not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'alias' => 'nullable|string|max:255|unique:products,alias,' . $id,
            'text' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'string|max:255',
            'is_sale' => 'boolean',
            'published' => 'boolean',
            'order' => 'nullable|integer',
            'price' => 'sometimes|required|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $product = $this->productAppService->getProductById((int)$id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        try {
            $updatedProduct = $this->productAppService->updateProduct(
                productId: (int)$id,
                title: $request->title,
                alias: $request->alias,
                text: $request->text,
                image: $request->image,
                images: $request->images,
                isSale: $request->is_sale,
                published: $request->published,
                order: $request->order,
                price: $request->price,
                userId: $request->user()->id,
                categoryIds: $request->categories
            );

            return new ProductResource($updatedProduct);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/v1/products/{id}",
     * operationId="deleteProduct",
     * tags={"Products"},
     * summary="Delete product",
     * description="Deletes a product record",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="Product ID",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Product deleted successfully"
     * ),
     * @OA\Response(
     * response=404,
     * description="Product not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $product = $this->productAppService->getProductById((int)$id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $this->productAppService->deleteProduct($id);

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     * path="/api/v1/products/search",
     * operationId="searchProducts",
     * tags={"Products"},
     * summary="Search for products",
     * description="Returns a list of products based on search criteria",
     * @OA\Parameter(
     * name="q",
     * in="query",
     * description="Search query for product title",
     * required=false,
     * @OA\Schema(type="string")
     * ),
     * @OA\Parameter(
     * name="min_price",
     * in="query",
     * description="Filter by minimum price",
     * required=false,
     * @OA\Schema(type="number")
     * ),
     * @OA\Parameter(
     * name="max_price",
     * in="query",
     * description="Filter by maximum price",
     * required=false,
     * @OA\Schema(type="number")
     * ),
     * @OA\Parameter(
     * name="on_sale",
     * in="query",
     * description="Filter by sale status",
     * required=false,
     * @OA\Schema(type="boolean")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product"))
     * )
     * )
     * )
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'nullable|string',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'on_sale' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $criteria = [
            'title' => $request->q,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'is_sale' => $request->on_sale,
        ];

        $products = $this->productAppService->searchProducts(array_filter($criteria));

        return response()->json([
            'data' => array_map(fn($product) => $product->toArray(), $products)
        ]);
    }
}
