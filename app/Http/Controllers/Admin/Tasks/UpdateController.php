<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use App\Services\Tasks\TaskDomainService;
use App\Services\Queries\FetchAllUsers\Query as FetchAllUsersQuery;
use App\Services\Queries\FetchAllUsers\Fetcher as UsersFetcher;
use App\Services\Queries\FetchAllCategories\Query as FetchAllCategoriesQuery;
use App\Services\Queries\FetchAllCategories\Fetcher as CategoriesFetcher;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateController extends Controller
{
    /**
     * Показать форму редактирования задачи
     */
    public function edit(
        TaskDomainService $taskService,
        UsersFetcher $usersFetcher, 
        CategoriesFetcher $categoriesFetcher, 
        string $taskId
    ): View {
        $task = $taskService->getTaskById((int)$taskId);
        
        if (!$task) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        // Получаем всех пользователей через фетчер
        $usersQuery = new FetchAllUsersQuery();
        $usersResult = $usersFetcher->fetch($usersQuery);
        $users = $usersResult->items;

        // Получаем все категории через фетчер
        $categoriesQuery = new FetchAllCategoriesQuery();
        $categoriesResult = $categoriesFetcher->fetch($categoriesQuery);
        $categories = $categoriesResult->items;

        // Priority пока оставляем как есть
        $priorities = Priority::all();

        return view('admin.tasks.edit', compact('task', 'users', 'categories', 'priorities'));
    }

    /**
     * Обновить данные задачи
     */
    public function update(UpdateTaskRequest $request, TaskDomainService $taskService, string $taskId)
    {
        $request->validated();

        $data = [
            'title' => $request->get('title'),
            'description' => $request->get('description', ''),
            'executor_id' => (int)$request->get('executor_id'),
            'category_id' => (int)$request->get('category_id'),
            'priority_id' => (int)$request->get('priority_id'),
            'due_date' => $request->get('due_date')
        ];

        $success = $taskService->updateTask((int)$taskId, $data);
        
        if (!$success) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        $task = $taskService->getTaskById((int)$taskId);

        return redirect()->route('admin.tasks.index')
            ->with('success', "Задача \"{$task->title}\" успешно обновлена");
    }
}
