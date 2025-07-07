<?php

namespace App\TodoApp\Presentation\Http\Controllers\ProjectUsers;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Application\UseCases\Queries\Project\FetchUsers\Query;
use App\TodoApp\Application\UseCases\Queries\Project\FetchUsers\Fetcher;

class Index extends Controller
{
    /**
     * Display the specified resource.
     */
    public function __invoke(int $projectId, Fetcher $fetcher): View
    {
        try {
            $result = $fetcher->fetch(new Query($projectId));

            return view('todo-app::projects.users.index', [
                'project' => $result->projectDTO,
                'users' => $result->userDTOs,
            ]);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
