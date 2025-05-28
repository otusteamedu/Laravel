<?php

namespace App\Http\Controllers\Projects;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Services\UseCases\Queries\Project\FetchWithRelations\Query;
use App\Services\UseCases\Queries\Project\FetchWithRelations\Fetcher;
use App\Services\UseCases\Queries\Project\FetchWithRelations\ModelNotFoundException;

class Show extends Controller
{
    /**
     * Display the specified resource.
     */
    public function __invoke(Fetcher $fetcher, int $projectId): View
    {
        try {
            $result = $fetcher->fetch(new Query($projectId));

            return view('projects.show', [
                'project'  => $result->ptojectDTO,
                'statuses' => $result->todoStatusDTOs,
            ]);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
