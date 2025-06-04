<?php

namespace App\Http\Controllers\Admin\Tasks;

use App\Http\Controllers\Controller;
use App\Services\Queries\FetchTaskById\Query;
use App\Services\Queries\FetchTaskById\Fetcher;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShowController extends Controller
{
    /**
     * Показать детали задачи
     */
    public function show(Fetcher $fetcher, string $taskId): View
    {
        try {
            $query = new Query((int)$taskId);
            $task = $fetcher->fetch($query);
        } catch (\Exception) {
            throw new NotFoundHttpException('Задача не найдена');
        }

        return view('admin.tasks.show', compact('task'));
    }
} 