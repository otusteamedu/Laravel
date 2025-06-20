<?php

namespace App\Http\Controllers\Public\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Queries\FetchAllTasks\Query as FetchAllTasksQuery;
use App\Services\Queries\FetchAllTasks\Fetcher as FetchAllTasksFetcher;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Показать список задач
     */
    public function index(Request $request, FetchAllTasksFetcher $fetcher): View
    {
        $this->authorize('viewAny', \App\Models\Task::class);
        
        $page = max(1, (int) $request->get('page', 1));
        $perPage = 20;
        
        $query = FetchAllTasksQuery::fromPage($page, $perPage);
        $paginatedResult = $fetcher->fetch($query);

        // Преобразуем PaginatedResult в LengthAwarePaginator для шаблона
        $tasks = new LengthAwarePaginator(
            items: $paginatedResult->items,
            total: $paginatedResult->total,
            perPage: $paginatedResult->getPerPage(),
            currentPage: $paginatedResult->getCurrentPage(),
            options: [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );

        $tasks->withQueryString();
        
        // Получаем модели Task для проверки Policy в представлении
        $taskIds = collect($paginatedResult->items)->pluck('id')->toArray();
        $taskModels = \App\Models\Task::whereIn('id', $taskIds)->get()->keyBy('id');

        return view('tasks.index', compact('tasks', 'taskModels'));
    }
} 