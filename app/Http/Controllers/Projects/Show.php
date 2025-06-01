<?php

namespace App\Http\Controllers\Projects;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Services\Repositories\Exceptions\ModelNotFoundException;
use App\Services\UseCases\Queries\Project\FetchWithRelations\Query;
use App\Services\UseCases\Queries\Project\FetchWithRelations\Fetcher;

class Show extends Controller
{
    /**
     * Display the specified resource.
     */
    public function __invoke(int $projectId, Fetcher $fetcher): View
    {
        try {
            $result = $fetcher->fetch(new Query($projectId));

            return view('projects.show', [
                'project' => $result->ptojectDTO,
                'users' => $result->userDTOs,
            ]);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
