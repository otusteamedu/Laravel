<?php

namespace App\Http\Controllers\Projects;

use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Project\UpdateRequest;
use App\Services\Repositories\ProjectRepositoryInterface;
use App\Services\UseCases\Commands\Project\Update\Command;
use App\Services\UseCases\Commands\Project\Update\Handler;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Update extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $projectId, ProjectRepositoryInterface $projectRepository): View
    {
        $project = $projectRepository->find($projectId);

        if (!$project) {
            abort(404);
        }

        return view('projects.update', [
            'id' => $project->projectId,
            'name' => $project->name,
            'description' => $project->description
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $projectId, UpdateRequest $request, Handler $handler): RedirectResponse
    {
        $data = $request->validated();

        try {
            $result = $handler->handle(
                new Command(
                    id: $projectId,
                    name: $data['name'],
                    description: $data['description'],
                )
            );

            return redirect(route('projects.show', ['projectId' => $projectId]))->with('success', 'Проект обновлен');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
