<?php

namespace App\Http\Controllers\Api\V1;

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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DomainException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\CreateNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Resources\NewsDTOResource;

class NewsController extends Controller
{
    public function __construct(
        private FetchAllFetcher $fetchAllFetcher,
        private FetchByIdFetcher $fetchByIdFetcher,
        private CreateNewsHandler $createNewsHandler,
        private UpdateNewsHandler $updateNewsHandler,
        private DeleteNewsHandler $deleteNewsHandler,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $limit = (int) $request->get('limit', 10);
        $page = (int) $request->get('page', 1);
        $offset = ($page - 1) * $limit;

        $query = new FetchAllQuery(limit: $limit, offset: $offset);
        $paginatedResult = $this->fetchAllFetcher->fetch($query);

        return response()->json([
                                    'data' => NewsDTOResource::collection($paginatedResult->items),
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
    public function store(CreateNewsRequest $request): JsonResponse
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
            $newsDTO = $this->createNewsHandler->handle($command);

            return response()->json(['data' => $newsDTO], Response::HTTP_CREATED);

        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);

        } catch (\Exception) {
            return response()->json(['message' => 'Ошибка при сохранении новости'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $query = new FetchByIdQuery($id);
            $newsDTO = $this->fetchByIdFetcher->fetch($query);

            return response()->json([
                                        'data' => new NewsDTOResource($newsDTO),
                                    ]);

        } catch (NewsNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsRequest $request, int $id): JsonResponse
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
            $payload = JWTAuth::parseToken()->getPayload();
            $isAdmin = (bool)$payload->get('admin', false);

            $newsDTO = $this->updateNewsHandler->handle($command, $isAdmin);

            return response()->json(['data' => $newsDTO]);

        } catch (NewsNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);

        } catch (DomainException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);

        } catch (NewsSaveException $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception) {
            return response()->json(['message' => 'Ошибка при обновлении новости'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->deleteNewsHandler->handle(new DeleteNewsCommand($id));

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
