<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Models\Priority;
use App\Services\Commands\CreateTask\Command;
use App\Services\Commands\CreateTask\Handler;
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
    public function store(CreateTaskRequest $request, Handler $handler)
    {
        $request->validated();

        $command = new Command(
            title: $request->get('title'),
            description: $request->get('description', ''),
            executorId: (int)$request->get('executor_id'),
            categoryId: (int)$request->get('category_id'),
            priorityId: (int)$request->get('priority_id'),
            creatorId: auth()->id(),
            status: $request->get('status', 'новая'),
            dueDate: $request->get('due_date')
        );

        $handler->handle($command);

        return redirect()->route('admin.tasks.index')
            ->with('success', "Задача успешно создана");
    }
}
