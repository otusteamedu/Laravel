<?php

namespace App\Http\Controllers\TodoStatuses;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TodoStatus\UpdateRequest;
use App\Services\UseCases\Commands\TodoStatus\Update\Command;
use App\Services\UseCases\Commands\TodoStatus\Update\Handler;
use App\Services\Repositories\Exceptions\ModelNotFoundException;

class Update extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function __invoke(UpdateRequest $request, Handler $handler): RedirectResponse
    {
        $data = $request->validated();

        try {
            $handler->handle(
                new Command(
                    statusId: $data['status_id'],
                    projectId: $data['project_id'],
                    name: $data['name'],
                    sort: $data['sort'],
                    color: $data['color'],
                )
            );

            return redirect()->back()->with('success', 'Статус для задачи обновлен');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
