<?php

namespace App\TodoApp\Presentation\Http\Controllers\TodoStatuses;

use App\Http\Controllers\Controller;
use App\TodoApp\Domain\Exceptions\CreateModelFailedException;
use App\TodoApp\Presentation\Http\Requests\TodoStatus\StoreRequest;
use App\TodoApp\Application\UseCases\Commands\TodoStatus\Create\Command;
use App\TodoApp\Application\UseCases\Commands\TodoStatus\Create\Handler;

class Create extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(StoreRequest $request, Handler $handler)
    {
        $data = $request->validated();

        try {
            $result = $handler->handle(
                new Command(
                    projectId: $data['project_id'],
                    name: $data['name'],
                    sort: $data['sort'],
                    color: $data['color'],
                )
            );

            return redirect()->back()->with('success', 'Статус задачи для проекта добавлен');
        } catch (CreateModelFailedException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }
    }
}
