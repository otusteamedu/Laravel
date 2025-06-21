<?php

namespace App\Http\Controllers\Todo;

use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Todo\UpdateRequest;
use App\Services\UseCases\Commands\Todo\Update\Command;
use App\Services\UseCases\Commands\Todo\Update\Handler;
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
                    todoId: $data['todo_id'],
                    projectId: $data['project_id'],
                    title: $data['title'],
                    description: $data['description'],
                    deadline: Carbon::parse($data['deadline']),
                    options: $data['options'] ?? []
                )
            );
            return redirect()->back()->with('success', 'Задача обновлена');
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }
}
