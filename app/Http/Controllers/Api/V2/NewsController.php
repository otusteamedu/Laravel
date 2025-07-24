<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Application\UseCases\News\Queries\FetchAllNewsPagination\Fetcher as FetchAllFetcher;
use App\Application\UseCases\News\Queries\FetchAllNewsPagination\Query as FetchAllQuery;
use App\Application\UseCases\News\Queries\FetchNewsById\Fetcher as FetchByIdFetcher;
use App\Application\UseCases\News\Queries\FetchNewsById\Query as FetchByIdQuery;
use App\Application\UseCases\News\Commands\CreateNews\Command as CreateNewsCommand;
use App\Application\UseCases\News\Commands\CreateNews\Handler as CreateNewsHandler;
use App\Application\UseCases\News\Commands\UpdateNews\Command as UpdateNewsCommand;
use App\Application\UseCases\News\Commands\UpdateNews\Handler as UpdateNewsHandler;
use App\Application\UseCases\News\Commands\DeleteNews\Command as DeleteNewsCommand;
use App\Application\UseCases\News\Commands\DeleteNews\Handler as DeleteNewsHandler;
use App\Domain\News\Exceptions\NewsNotFoundException;
use App\Domain\News\Exceptions\NewsSaveException;
use Illuminate\Auth\AuthManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DomainException;
use App\Http\Requests\CreateNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Resources\NewsResource;
use App\Http\Resources\Mappers\NewsApiModelMapper;

class NewsController extends Controller
{
    public function __construct(
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(FetchAllFetcher $fetchAllFetcher, Request $request): JsonResponse
    {
        $limit = (int) $request->get('limit', 10);
        $page = (int) $request->get('page', 1);
        $offset = ($page - 1) * $limit;

        $query = new FetchAllQuery(limit: $limit, offset: $offset);
        $paginatedResult = $fetchAllFetcher->fetch($query);

        $apiModels = array_map(
            fn($newsDTO) => NewsApiModelMapper::map($newsDTO),
            $paginatedResult->items
        );

        return response()->json([
                                    'data' => NewsResource::collection($apiModels),
                                    'meta' => [
                                        'total' => $paginatedResult->total,
                                        'limit' => $paginatedResult->limit,
                                        'offset' => $paginatedResult->offset,
                                    ],
                                ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewsHandler $createNewsHandler, CreateNewsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $command = new CreateNewsCommand(
            title: $validated['title'],
            content: $validated['content'],
            authorId: $validated['author_id'],
            categoryId: $validated['category_id'],
            publishedAt: isset($validated['published_at']) ? new \DateTimeImmutable($validated['published_at']) : null,
            isDraft: $validated['is_draft'],
           // thumbnail: $validated['thumbnail'] ?? null,
        );

        try {
            $newsDTO = $createNewsHandler->handle($command);
            $apiModel = NewsApiModelMapper::map($newsDTO);

            return response()->json(['data' => $apiModel], Response::HTTP_CREATED);

        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);

        } catch (\Exception) {
            return response()->json(['message' => 'Ошибка при сохранении новости'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(FetchByIdFetcher $fetchByIdFetcher, int $id): JsonResponse
    {
        try {
            $query = new FetchByIdQuery($id);
            $newsDTO = $fetchByIdFetcher->fetch($query);

            $apiModel = NewsApiModelMapper::map($newsDTO);

            return response()->json(['data' => new NewsResource($apiModel)]);

        } catch (NewsNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsHandler $updateNewsHandler, AuthManager $authManager, UpdateNewsRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        $command = new UpdateNewsCommand(
            id: $id,
            title: $validated['title'],
            content: $validated['content'],
            categoryId: $validated['category_id'],
            authorId: $validated['author_id'] ?? null,
            publishedAt: isset($validated['published_at']) ? new \DateTimeImmutable($validated['published_at']) : null,
            isDraft: $validated['is_draft'],
           // thumbnail: $validated['thumbnail'] ?? null,
        );

        try {

            $user = $authManager->user();
            $isAdmin = $user->hasRole('admin');

            $newsDTO = $updateNewsHandler->handle($command, $isAdmin);
            $apiModel = NewsApiModelMapper::map($newsDTO);

            return response()->json(['data' => new NewsResource($apiModel)]);

        } catch (NewsNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);

        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);

        } catch (NewsSaveException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ошибка при обновлении новости'. $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteNewsHandler $deleteNewsHandler, int $id)
    {
        try {
            $deleteNewsHandler->handle(new DeleteNewsCommand($id));

            return response()->noContent();

        } catch (NewsNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);

        } catch (NewsSaveException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (\Exception) {
            return response()->json(['message' => 'Ошибка при удалении новости'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
