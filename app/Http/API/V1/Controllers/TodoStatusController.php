<?php

namespace App\Http\API\V1\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\API\V1\Requests\ShowRequest;
use App\Http\API\V1\Requests\StoreRequest;
use App\Http\API\V1\Requests\UpdateRequest;
use App\Http\API\V1\Responses\ErrorResponse;
use App\Http\API\V1\Responses\SuccessResponse;
use App\Services\Repositories\Exceptions\ModelNotFoundException;
use App\Services\UseCases\Queries\TodoStatus\FetchOne\Query as ShowQuery;
use App\Services\UseCases\Commands\TodoStatus\Create\Command as StoreCommand;
use App\Services\UseCases\Commands\TodoStatus\Create\Handler as StoreHandler;
use App\Services\UseCases\Queries\TodoStatus\FetchOne\Fetcher as ShowFetcher;
use App\Services\UseCases\Commands\TodoStatus\Update\Command as UpdateCommand;
use App\Services\UseCases\Commands\TodoStatus\Update\Handler as UpdateHandler;
use App\Services\UseCases\Commands\TodoStatus\Delete\Command as DestroyCommand;
use App\Services\UseCases\Commands\TodoStatus\Delete\Handler as DestroyHandler;
use App\Services\UseCases\Queries\TodoStatus\FetchForProject\Query as IndexQuery;
use App\Services\UseCases\Queries\TodoStatus\FetchForProject\Fetcher as IndexFetcher;


class TodoStatusController extends Controller
{
    /**
     * Получить список статусов задач для проекта.
     */
    public function index(int $projectId, IndexFetcher $fetcher): JsonResponse
    {
        try {
            $payload = $fetcher->fetch(new IndexQuery($projectId));

            return new JsonResponse(
                new SuccessResponse(
                    payload: $payload->todostatusDTOs,
                    success: true,
                    code: 200
                )
            );
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse(
                new ErrorResponse(
                    message: $exception->getMessage(),
                    errors: [],
                    code: 404,
                )
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                new ErrorResponse(
                    message: $exception->getMessage(),
                    errors: [],
                    code: 422,
                )
            );
        }
    }

    /**
     * Добавить новый статус задачи для проекта.
     */
    public function store(StoreRequest $request, StoreHandler $handler): JsonResponse
    {
        $data = $request->validated();

        try {
            $result = $handler->handle(
                new StoreCommand(
                    projectId: $data['project_id'],
                    name: $data['name'],
                    sort: $data['sort'],
                    color: $data['color'],
                )
            );

            return new JsonResponse(
                new SuccessResponse(
                    payload: $result,
                    success: true,
                    code: 200
                )
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                new ErrorResponse(
                    message: $exception->getMessage(),
                    errors: [],
                    code: 422,
                )
            );
        }
    }

    /**
     * Получить данные о статусе задачи для проекта.
     */
    public function show(ShowRequest $request, ShowFetcher $fetcher): JsonResponse
    {
        $data = $request->validated();

        try {
            $payload = $fetcher->fetch(new ShowQuery(
                projectId: $data['project_id'],
                statusId: $data['status_id']
            ));

            return new JsonResponse(
                new SuccessResponse(
                    payload: $payload,
                    success: true,
                    code: 200
                )
            );
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse(
                new ErrorResponse(
                    message: $exception->getMessage(),
                    errors: [],
                    code: 404,
                )
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                new ErrorResponse(
                    message: $exception->getMessage(),
                    errors: [],
                    code: 422,
                )
            );
        }
    }

    /**
     * Обновить данне статуса задачи для проекта
     */
    public function update(UpdateRequest $request, UpdateHandler $handler): JsonResponse
    {
        $data = $request->validated();

        try {
            $success = $handler->handle(
                new UpdateCommand(
                    statusId: $data['status_id'],
                    projectId: $data['project_id'],
                    name: $data['name'],
                    sort: $data['sort'],
                    color: $data['color'],
                )
            );

            return new JsonResponse(
                new SuccessResponse(
                    payload: [],
                    success: $success,
                    code: 200
                )
            );
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse(
                new ErrorResponse(
                    message: $exception->getMessage(),
                    errors: [],
                    code: 404,
                )
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                new ErrorResponse(
                    message: $exception->getMessage(),
                    errors: [],
                    code: 422,
                )
            );
        }
    }

    /**
     * Удалить статус задачи для проекта
     */
    public function destroy(ShowRequest $request, DestroyHandler $handler): JsonResponse
    {
        $data = $request->validated();

        try {
            $success = $handler->handle(
                new DestroyCommand(
                    statusId: $data['status_id'],
                    projectId: $data['project_id'],
                )
            );

            return new JsonResponse(
                new SuccessResponse(
                    payload: [],
                    success: $success,
                    code: 200
                )
            );
        } catch (ModelNotFoundException $exception) {
            return new JsonResponse(
                new ErrorResponse(
                    message: $exception->getMessage(),
                    errors: [],
                    code: 404,
                )
            );
        } catch (Exception $exception) {
            return new JsonResponse(
                new ErrorResponse(
                    message: $exception->getMessage(),
                    errors: [],
                    code: 422,
                )
            );
        }
    }
}
