@php
/**
 * @var \App\Services\Repositories\DTOs\ProjectDTO $project
 * @var \App\Services\Repositories\Todo\TodoFetchDTO[] $todos
*/
@endphp
@extends('layouts.main')
@section('title', "ToDo: Задачи проекта $project->name")
@section('content')
<div class="col-12">
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="row g-0">
                <nav class="col-lg-3 border-end">
                    @include('projects.partials.nav', [
                        'active'    => 'todos',
                        'projectId' => $project->projectId,
                    ])
                </nav>
                <div class="col-lg-9">
                    <div class="p-4" id="statuses">
                        <div class="mb-4">
                            <h4 class="mb-4">Задачи проектa {{ $project->name }}</h4>
                            @can('todostatuses.manage', $project->projectId)
                                <div class="col-12 my-3 text-end">
                                    <button
                                        type="button" 
                                        class="btn btn-outline-primary"
                                        x-data @click="$store.todoStatuses.formShow()"
                                        >
                                        Добавить статус
                                    </button>
                                </div>
                            @endcan
                            <div class="d-flex flex-wrap">
                                @foreach($todos as $todo)
                                    @include('todos.partials.todo-card', [
                                        'project' => $project,
                                        'todo' => $todo,
                                    ])
                                @endforeach                        
                            </div>
                        </div>
                        <div class="mb-4">
                            @include('todos.statuses.form', [
                                'projectId' => $project->projectId,
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
