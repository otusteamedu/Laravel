<?php

namespace App\TodoApp\Presentation\Http\Controllers\Projects;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\TodoApp\Application\UseCases\Queries\Project\Fetch\Query;
use App\TodoApp\Domain\Exceptions\ModelNotFoundException;
use App\TodoApp\Application\UseCases\Queries\Project\Fetch\Fetcher;
use Illuminate\Support\Carbon;

class Show extends Controller
{
    /**
     * Display the specified resource.
     */
    public function __invoke(int $projectId, Fetcher $fetcher, Carbon $carbon): View
    {
        if (! Gate::allows('project.view', $projectId)) {
            abort(404);
        }

        try {
            $result = $fetcher->fetch(new Query($projectId));

            return view('todo-app::projects.show', [
                'projectDTO' => $result->projectDTO,
                'carbon'     => $carbon,
            ]);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
