@php
/**
 * @var \App\Services\Repositories\DTOs\ProjectDTO $project
 * @var \App\Services\Repositories\Todo\TodoFetchDTO $todo
*/
@endphp
@extends('layouts.main')
@section('title', "ToDo: Задача проекта $project->name - $todo->title")
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
                    <div x-data="{showEdit: @if(session('error')) true @else false @endif}" 
                        x-cloak
                        class="p-4" id="todo-{{ $todo->todoId }}"
                    >
                        <div  x-show="!showEdit" class="mb-4">
                            <h4 class="mb-4">Задача {{ $todo->title }}</h4>
                            <div class="border col-12 d-flex flex-column flex-lg-row mb-2 rounded-2">
                                <div class="col-12 col-lg-9 p-3">
                                    {!! $todo->description !!}
                                </div>
                                <div class="col-12 col-lg-3 p-3">
                                    @if (!empty($todo->options['isHot']))
                                        <div class="">
                                            <i class="fa-solid fa-fire text-danger"></i><small class="ms-2">Важная</small>
                                        </div>
                                    @endif
                                    <div class="">
                                        <small class="fw-bold">Крайний срок:</small>
                                        <small class="ms-2">{{ $todo->deadline->format("d.m.Y") }}</small>
                                    </div>
                                    <div class="">
                                        <small class="fw-bold">Статус:</small>
                                        <small class="ms-2">{{ $todo->status->name }}</small>
                                    </div>
                                    <div class="">
                                        <small class="fw-bold">Постановщик:</small>
                                        <small class="ms-2">{{ $todo->author->name }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-4 d-flex">
                                <span class="border border-secondary btn btn-sm text-muted"
                                    @click="showEdit=true"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Редактировать
                                </span>
                            </div>
                            @if(!empty($todo->options['embed']))
                                <div class="video col-12 mb-4">
                                    <embed src="{{ $todo->options['embed']->video }}"/>
                                </div>
                            @endif
                        </div>
                        <div x-show="showEdit" class="pt-4">
                                <h4 class="mb-4">Редактирование задачи</h4>
                                <form id="todo-edit-form-{{ $todo->todoId }}" method="POST" 
                                    action="{{ route('project.todos.update', ['projectId' => $project->projectId, 'todoId' => $todo->todoId]) }}" autocomplete="off">
                                    @csrf
                                    @method('put')
                                    @include('todos.partials.form-fields', ['todo' => $todo])
                                    <div class="col-12 my-2 text-end">
                                        <button class="btn btn-outline-primary">Сохранить</button>
                                    </div>
                                </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
