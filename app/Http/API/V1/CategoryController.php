<?php

namespace App\Http\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="Categories V1",
 *     description="API Endpoints for Category Management (Version 1)"
 * )
 */
class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     operationId="getCategoriesListV1",
     *     tags={"Categories V1"},
     *     summary="Get all categories",
     *     description="Returns paginated list of categories with optional products",
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
     *         description="Items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Parameter(
     *         name="with_products",
     *         in="query",
     *         description="Include products in response",
     *         required=false,
     *         @OA\Schema(type="boolean", default=false)
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
     *         description="Sort field (order, title, created_at)",
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
     *         @OA\JsonContent(ref="#/components/schemas/CategoryCollection")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'with_products' => 'sometimes|boolean',
            'published' => 'sometimes|boolean',
            'sort' => 'sometimes|in:order,title,created_at',
            'direction' => 'sometimes|in:asc,desc',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        $perPage = $validated['per_page'] ?? 15;
        $withProducts = $validated['with_products'] ?? false;
        $sort = $validated['sort'] ?? 'order';
        $direction = $validated['direction'] ?? 'asc';

        $query = Category::query();

        if ($withProducts) {
            $query->with(['products' => function($query) {
                $query->select('products.id', 'title', 'price', 'image');
            }]);
        }

        if (isset($validated['published'])) {
            $query->where('published', $validated['published']);
        }

        $categories = $query->orderBy($sort, $direction)
            ->paginate($perPage);

        return new CategoryCollection($categories);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/categories",
     *     operationId="createCategoryV1",
     *     tags={"Categories V1"},
     *     summary="Create new category",
     *     description="Creates a new category",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Category created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'alias' => 'nullable|string|max:255|unique:categories,alias',
            'text' => 'nullable|string',
            'published' => 'boolean',
            'order' => 'nullable|integer',
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

        $category = Category::create($data);

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/categories/{id}",
     *     operationId="getCategoryByIdV1",
     *     tags={"Categories V1"},
     *     summary="Get category details",
     *     description="Returns category data with optional products",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="with_products",
     *         in="query",
     *         description="Include products in response",
     *         required=false,
     *         @OA\Schema(type="boolean", default=false)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     )
     * )
     */
    public function show(Request $request, Category $category)
    {
        if ($request->boolean('with_products', false)) {

            $category->load(['products' => function($query) {
                $query->select('products.id', 'title', 'price', 'image');
            }]);
        }

        return new CategoryResource($category);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/categories/{id}",
     *     operationId="updateCategoryV1",
     *     tags={"Categories V1"},
     *     summary="Update existing category",
     *     description="Updates category record",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Category")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
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
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'alias' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id)
            ],
            'text' => 'nullable|string',
            'published' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if (isset($data['title']) && empty($data['alias'])) {
            $data['alias'] = Str::slug($data['title']);
        }

        $category->update($data);

        return new CategoryResource($category);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/categories/{id}",
     *     operationId="deleteCategoryV1",
     *     tags={"Categories V1"},
     *     summary="Delete category",
     *     description="Deletes category record",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Category ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Category deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
