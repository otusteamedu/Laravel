<?php

namespace App\Http\Controllers\Public\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Queries\FetchTaskById\Query as FetchTaskByIdQuery;
use App\Services\Queries\FetchTaskById\Fetcher as FetchTaskByIdFetcher;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowController extends Controller
{
    /**
     * Показать детальную информацию о задаче
     */
    public function show(FetchTaskByIdFetcher $fetcher, string $taskId): View
    {
        // Получаем модель Task для авторизации
        $taskModel = \App\Models\Task::findOrFail($taskId);
        $this->authorize('view', $taskModel);
        
        try {
            $query = new FetchTaskByIdQuery((int)$taskId);
            $task = $fetcher->fetch($query);
        } catch (\Exception) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        return view('tasks.show', compact('task', 'taskModel'));
    }
} 