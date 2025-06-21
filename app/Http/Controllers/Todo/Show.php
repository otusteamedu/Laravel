<?php

namespace App\Http\Controllers\Todo;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\UseCases\Queries\Todo\Fetch\Query;
use App\Services\UseCases\Queries\Todo\Fetch\Fetcher;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Show extends Controller
{
    /**
     * View todo
     */
    public function __invoke(int $projectId, int $todoId, Fetcher $fetcher): View|RedirectResponse
    {
        try {
            $result = $fetcher->fetch(new Query($projectId, $todoId));
            return view('todos.show', ['project' => $result->projectDTO, 'todo' => $result->todoDTO]);
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
