<?php

namespace App\Interface\Http\API\V1;

use App\Application\Services\CategoryAppService;
use App\Infrastructure\Eloquent\Models\Category;
use App\Interface\Http\Controllers\Controller;
use App\Interface\Http\Resources\CategoryCollection;
use App\Interface\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 * name="Categories V1",
 * description="API Endpoints for Category Management (Version 1)"
 * )
 *
 * @OA\Schema(
 * schema="CategoryRequest",
 * title="Category Request",
 * description="Data structure for creating or updating a category",
 * @OA\Property(property="title", type="string", description="Title of the category", example="Electronics"),
 * @OA\Property(property="alias", type="string", description="Unique URL alias for the category", example="electronics"),
 * @OA\Property(property="text", type="string", description="Category description", nullable=true, example="A wide range of electronic devices."),
 * @OA\Property(property="published", type="boolean", description="Publication status of the category", nullable=true, example="true"),
 * @OA\Property(property="order", type="integer", description="Display order of the category", nullable=true, example="10"),
 * )
 */
class CategoryController extends Controller
{
    public function __construct(
        private CategoryAppService $categoryAppService
    ) {}
    /**
     * @OA\Get(
     * path="/api/v1/categories",
     * operationId="getCategoriesListV1",
     * tags={"Categories V1"},
     * summary="Get all categories",
     * description="Returns paginated list of categories with optional products",
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
     * name="with_products",
     * in="query",
     * description="Include products with categories",
     * required=false,
     * @OA\Schema(type="boolean", default=false)
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="object",
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(ref="#/components/schemas/Category")
     * )
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'sometimes|integer|min:1|max:100',
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
        $page = $validated['page'] ?? 1;
        $sort = $validated['sort'] ?? 'order';
        $direction = $validated['direction'] ?? 'asc';

        $criteria = [
            'published' => $request->published,
            'sort' => $sort,
            'direction' => $direction,
        ];

        $result = $this->categoryAppService->getCategoriesPaginated($page, $perPage, $criteria);


        return response()->json([
            'data' => array_map(fn($category) => $category->toArray(), $result['data']),
            'meta' => [
                'current_page' => $result['current_page'],
                'per_page' => $result['per_page'],
                'total' => $result['total'],
                'last_page' => $result['last_page'],
            ]
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/v1/categories",
     * operationId="createCategoryV1",
     * tags={"Categories V1"},
     * summary="Create new category",
     * description="Creates a new category record",
     * security={{"bearerAuth": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     * ),
     * @OA\Response(
     * response=201,
     * description="Category created successfully",
     * @OA\JsonContent(ref="#/components/schemas/Category")
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

        try {
            $category = $this->categoryAppService->createCategory(
                title: $data['title'],
                alias: $data['alias'],
                text: $data['text'] ?? null,
                published: (bool)($data['published'] ?? true),
                order: (int)($data['order'] ?? 0)
            );

            return (new CategoryResource($category))
                ->response()
                ->setStatusCode(201);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Get(
     * path="/api/v1/categories/{id}",
     * operationId="getCategoryByIdV1",
     * tags={"Categories V1"},
     * summary="Get category by ID",
     * description="Returns a single category record",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="Category ID",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/Category")
     * ),
     * @OA\Response(
     * response=404,
     * description="Category not found"
     * )
     * )
     */
    public function show($id)
    {
        $category = $this->categoryAppService->getCategoryById((int)$id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        return new CategoryResource($category);
    }

    /**
     * @OA\Put(
     * path="/api/v1/categories/{id}",
     * operationId="updateCategoryV1",
     * tags={"Categories V1"},
     * summary="Update existing category",
     * description="Updates an existing category record by ID",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="Category ID",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(ref="#/components/schemas/CategoryRequest")
     * ),
     * @OA\Response(
     * response=200,
     * description="Category updated successfully",
     * @OA\JsonContent(ref="#/components/schemas/Category")
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error"
     * ),
     * @OA\Response(
     * response=404,
     * description="Category not found"
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
            'alias' => 'nullable|string|max:255|unique:categories,alias,' . $id,
            'text' => 'nullable|string',
            'published' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $category = $this->categoryAppService->getCategoryById((int)$id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        $data = $validator->validated();

        if (isset($data['title']) && empty($data['alias'])) {
            $data['alias'] = Str::slug($data['title']);
        }

        try {
            $updatedCategory = $this->categoryAppService->updateCategory(
                id: (int)$id,
                title: $data['title'],
                alias: $data['alias'],
                text: $data['text'] ?? null,
                published: (bool)($data['published'] ?? true),
                order: (int)($data['order'] ?? 0)
            );

            return new CategoryResource($updatedCategory);

        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/v1/categories/{id}",
     * operationId="deleteCategoryV1",
     * tags={"Categories V1"},
     * summary="Delete category",
     * description="Deletes category record",
     * security={{"bearerAuth": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="Category ID",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=204,
     * description="Category deleted successfully"
     * ),
     * @OA\Response(
     * response=404,
     * description="Category not found"
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated"
     * )
     * )
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(null, 204);
    }
}
