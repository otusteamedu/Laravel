<?php

namespace App\Http\Controllers\Public\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Queries\FetchAllTasks\Query as FetchAllTasksQuery;
use App\Services\Queries\FetchAllTasks\Fetcher as FetchAllTasksFetcher;
use Illuminate\View\View;

class IndexController extends Controller
{
    /**
     * Показать список задач
     */
    public function index(FetchAllTasksFetcher $fetcher): View
    {
        $this->authorize('viewAny', \App\Models\Task::class);
        
        $query = new FetchAllTasksQuery(perPage: 20);
        $tasks = $fetcher->fetch($query);
        
        // Получаем модели Task для проверки Policy в представлении
        $taskIds = $tasks->pluck('id')->toArray();
        $taskModels = \App\Models\Task::whereIn('id', $taskIds)->get()->keyBy('id');

        return view('tasks.index', compact('tasks', 'taskModels'));
    }
} 