<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTaskRequest;
use App\Http\Requests\Api\UpdateTaskRequest;

use App\Services\Commands\CreateTask\Command as CreateTaskCommand;
use App\Services\Commands\CreateTask\Handler as CreateTaskHandler;
use App\Services\Commands\UpdateTask\Command as UpdateTaskCommand;
use App\Services\Commands\UpdateTask\Handler as UpdateTaskHandler;
use App\Services\Commands\DeleteTask\Command as DeleteTaskCommand;
use App\Services\Commands\DeleteTask\Handler as DeleteTaskHandler;
use App\Services\Queries\FetchAllTasks\Query as FetchAllTasksQuery;
use App\Services\Queries\FetchAllTasks\Fetcher as FetchAllTasksFetcher;
use App\Services\Queries\FetchTaskById\Query as FetchTaskByIdQuery;
use App\Services\Queries\FetchTaskById\Fetcher as FetchTaskByIdFetcher;
use App\Services\Exceptions\Tasks\TaskNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/tasks",
     *     summary="Get tasks",
     *     tags={"Tasks"}
     * )
     */
    public function index(Request $request, FetchAllTasksFetcher $fetcher): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $perPage = (int) $request->get('per_page', 15);
        
        $query = FetchAllTasksQuery::fromPage($page, $perPage);
        $result = $fetcher->fetch($query);

        return response()->json([
            'data' => $result->items,
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $result->total,
                'last_page' => ceil($result->total / $perPage),
            ]
        ]);
    }

    /**
     * @OA\Post(
     *     path="/tasks",
     *     summary="Create task",
     *     tags={"Tasks"}
     * )
     */
    public function store(StoreTaskRequest $request, CreateTaskHandler $handler): JsonResponse
    {
        $command = new CreateTaskCommand(
            title: $request->title,
            description: $request->description ?? '',
            executorId: (int) $request->executor_id,
            categoryId: (int) $request->category_id,
            priorityId: (int) $request->priority_id,
            creatorId: auth('api')->id(),
            status: $request->status ?? 'новая',
            dueDate: $request->due_date
        );

        $result = $handler->handle($command);

        if ($result) {
            return response()->json([
                'message' => 'Задача успешно создана'
            ], 201);
        }

        return response()->json([
            'message' => 'Ошибка при создании задачи'
        ], 500);
    }

    /**
     * Получить задачу по ID
     */
    public function show(int $id, FetchTaskByIdFetcher $fetcher): JsonResponse
    {
        try {
            $query = new FetchTaskByIdQuery($id);
            $taskDTO = $fetcher->fetch($query);

            return response()->json([
                'data' => $taskDTO
            ]);
        } catch (TaskNotFoundException $e) {
            return response()->json([
                'message' => 'Задача не найдена'
            ], 404);
        }
    }

    /**
     * Обновить задачу
     */
    public function update(int $id, UpdateTaskRequest $request, UpdateTaskHandler $handler): JsonResponse
    {
        try {
            $command = new UpdateTaskCommand(
                id: $id,
                title: $request->title,
                description: $request->description ?? '',
                executorId: (int) $request->executor_id,
                categoryId: (int) $request->category_id,
                priorityId: (int) $request->priority_id,
                creatorId: auth('api')->id(),
                status: $request->status ?? 'новая',
                dueDate: $request->due_date
            );

            $taskDTO = $handler->handle($command);

            return response()->json([
                'message' => 'Задача успешно обновлена',
                'data' => $taskDTO
            ]);
        } catch (TaskNotFoundException $e) {
            return response()->json([
                'message' => 'Задача не найдена'
            ], 404);
        }
    }

    /**
     * Удалить задачу
     */
    public function destroy(int $id, DeleteTaskHandler $handler): JsonResponse
    {
        try {
            $command = new DeleteTaskCommand($id);
            $result = $handler->handle($command);

            if ($result) {
                return response()->json([
                    'message' => 'Задача успешно удалена'
                ]);
            }

            return response()->json([
                'message' => 'Ошибка при удалении задачи'
            ], 500);
        } catch (TaskNotFoundException $e) {
            return response()->json([
                'message' => 'Задача не найдена'
            ], 404);
        }
    }
}

