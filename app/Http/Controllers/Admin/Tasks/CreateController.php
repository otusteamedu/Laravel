<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use App\Services\Tasks\TaskDomainService;
use App\Services\Queries\FetchAllUsers\Query as FetchAllUsersQuery;
use App\Services\Queries\FetchAllUsers\Fetcher as UsersFetcher;
use App\Services\Queries\FetchAllCategories\Query as FetchAllCategoriesQuery;
use App\Services\Queries\FetchAllCategories\Fetcher as CategoriesFetcher;
use App\Http\Requests\CreateTaskRequest;

class CreateController extends Controller
{
    /**
     * Показать форму для создания задачи
     */
    public function create(UsersFetcher $usersFetcher, CategoriesFetcher $categoriesFetcher)
    {
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

        return view('admin.tasks.create', compact('users', 'categories', 'priorities'));
    }

    /**
     * Сохранить новую задачу
     */
    public function store(CreateTaskRequest $request, TaskDomainService $taskService)
    {
        $request->validated();

        $data = [
            'title' => $request->get('title'),
            'description' => $request->get('description', ''),
            'executor_id' => (int)$request->get('executor_id'),
            'category_id' => (int)$request->get('category_id'),
            'priority_id' => (int)$request->get('priority_id'),
            'creator_id' => auth()->id(),
            'due_date' => $request->get('due_date')
        ];

        $taskService->createTask($data);

        return redirect()->route('admin.tasks.index')
            ->with('success', "Задача успешно создана");
    }
}
