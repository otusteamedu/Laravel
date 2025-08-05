<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Tasks\TaskDomainService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexController extends Controller
{
    /**
     * Показать список задач
     */
    public function index(Request $request, TaskDomainService $taskService)
    {
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        
        $paginatedResult = $taskService->getPaginatedTasks($perPage, $offset);

        // Преобразуем PaginatedResult в LengthAwarePaginator для шаблона
        $tasks = new LengthAwarePaginator(
            items: $paginatedResult->items,
            total: $paginatedResult->total,
            perPage: $perPage,
            currentPage: $page,
            options: [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );

        $tasks->withQueryString();

        return view('admin.tasks.index', compact('tasks'));
    }
} 