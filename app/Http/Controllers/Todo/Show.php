<?php

namespace App\Http\Controllers\Todo;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\UseCases\Commands\Todo\Show\Command;
use App\Services\UseCases\Commands\Todo\Show\Handler;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Show extends Controller
{
    /**
     * View todo
     */
    public function __invoke(int $projectId, int $todoId, Handler $handler): View|RedirectResponse
    {
        try {
            $result = $handler->handle(new Command($projectId, $todoId));
            debugbar()->info($result);
            return view('todos.show', [
                'project' => $result->projectDTO,
                'todo' => $result->todoDTO,
                'projectUsers' => $result->projectUsers,
                'responsibles' => $result->responsibles,
                'performers' => $result->performers,
                'watchers' => $result->watchers
            ]);
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
