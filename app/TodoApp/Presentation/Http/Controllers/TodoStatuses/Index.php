<?php

namespace App\TodoApp\Presentation\Http\Controllers\TodoStatuses;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Application\UseCases\Queries\TodoStatus\FetchForProject\Query;
use App\TodoApp\Application\UseCases\Queries\TodoStatus\FetchForProject\Fetcher;


class Index extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(int $projectId, Fetcher $fetcher): View
    {
        try {
            $result = $fetcher->fetch(new Query($projectId));

            return view('todo-app::todos.statuses.index', [
                'project'  => $result->projectDTO,
                'statuses' => $result->todostatusDTOs,
            ]);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
