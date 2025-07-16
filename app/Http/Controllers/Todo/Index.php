<?php

namespace App\Http\Controllers\Todo;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Domain\Repositories\Project\ValueObject\ProjectRoleEnum;
use Illuminate\Support\Facades\Auth;
use App\Domain\Repositories\Project\Contracts\ProjectRepositoryInterface;
use App\Application\UseCases\Queries\Todo\FetchForProject\Query;
use App\Application\UseCases\Queries\Todo\FetchForProject\Fetcher;
use App\Domain\Repositories\Exceptions\ModelNotFoundException;

class Index extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(int $projectId, ProjectRepositoryInterface $repository, Fetcher $fetcher): View
    {
        try {
            if ($repository->userHasRole($projectId, Auth::id(), [ProjectRoleEnum::ADMIN])) {
                $result = $fetcher->fetch(new Query($projectId));
            } else {
                $result = $fetcher->fetch(new Query($projectId, Auth::id()));
            }

            return view('todos.index', [
                'project'  => $result->projectDTO,
                'todos' => $result->todoDTOs,
            ]);
        } catch (ModelNotFoundException) {
            abort(404);
        }
    }
}
