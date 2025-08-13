<?php

namespace App\Http\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="Products V1",
 *     description="API Endpoints for Product Management (Version 1)"
 * )
 */
class ProductsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     operationId="getProductsListV1",
     *     tags={"Products V1"},
     *     summary="Get all products",
     *     description="Returns paginated list of products with their categories",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Items per page (default: 15)",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Filter by category ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="is_sale",
     *         in="query",
     *         description="Filter by sale status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="published",
     *         in="query",
     *         description="Filter by published status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort field (price, order, created_at)",
     *         required=false,
     *         @OA\Schema(type="string", default="order")
     *     ),
     *     @OA\Parameter(
     *         name="direction",
     *         in="query",
     *         description="Sort direction (asc, desc)",
     *         required=false,
     *         @OA\Schema(type="string", default="asc")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ProductCollection")
     *     )
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

        $validated = $validator->validated();
        $perPage = $validated['per_page'] ?? 15;
        $sort = $validated['sort'] ?? 'order';
        $direction = $validated['direction'] ?? 'asc';

        $query = Product::with(['categories' => function($query) {
            $query->select('id', 'title', 'alias');
        }]);

        if (isset($validated['category_id'])) {
            $query->whereHas('categories', function($q) use ($validated) {
                $q->where('id', $validated['category_id']);
            });
        }

        if (isset($validated['is_sale'])) {
            $query->where('is_sale', $validated['is_sale']);
        }

        if (isset($validated['published'])) {
            $query->where('published', $validated['published']);
        }

        $products = $query->orderBy($sort, $direction)
            ->paginate($perPage);

        return new ProductCollection($products);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/products",
     *     operationId="createProductV1",
     *     tags={"Products V1"},
     *     summary="Create new product",
     *     description="Creates a new product with categories",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     type="array",
     *                     @OA\Items(type="string", example="The title field is required.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255|unique:products,alias',
            'text' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'is_sale' => 'boolean',
            'published' => 'boolean',
            'order' => 'nullable|integer',
            'price' => 'required|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if (empty($data['alias'])) {
            $data['alias'] = Str::slug($data['title']);
        }

        if (isset($data['images'])) {
            $data['images'] = json_encode($data['images']);
        }

        $product = Product::create($data);

        if (isset($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }

        // Загружаем категории для нового продукта
        $product->load('categories');

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products/{id}",
     *     operationId="getProductByIdV1",
     *     tags={"Products V1"},
     *     summary="Get product details",
     *     description="Returns product data with categories",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     )
     * )
     */
    public function show(Product $product)
    {
        $product->load(['categories' => function($query) {
            $query->select('id', 'title', 'alias');
        }]);
        return new ProductResource($product);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/products/{id}",
     *     operationId="updateProductV1",
     *     tags={"Products V1"},
     *     summary="Update existing product",
     *     description="Updates product and its categories",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProductRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'alias' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($product->id)
            ],
            'text' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'is_sale' => 'boolean',
            'published' => 'boolean',
            'order' => 'nullable|integer',
            'price' => 'sometimes|required|numeric|min:0',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if (isset($data['images'])) {
            $data['images'] = json_encode($data['images']);
        }

        if (isset($data['price']) && $data['price'] != $product->price) {
            // You can add price change tracking logic here
        }

        $product->update($data);

        if (isset($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }

        $product->load('categories');

        return new ProductResource($product->fresh()->load('categories'));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/products/{id}",
     *     operationId="deleteProductV1",
     *     tags={"Products V1"},
     *     summary="Delete product",
     *     description="Deletes a product",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
