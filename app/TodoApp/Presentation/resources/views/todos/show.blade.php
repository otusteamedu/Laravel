@php
/**
 * @var \App\TodoApp\Application\DTOs\ProjectDTO $project
 * @var \App\Services\Repositories\Todo\TodoFetchDTO $todo
 * @var \App\TodoApp\Application\DTOs\ProjectInvitedUserDTO[] $projectUsers
 * @var \App\Services\Repositories\Todo\TodoUserDTO[] $responsibles
 * @var \App\Services\Repositories\Todo\TodoUserDTO[] $performers
 * @var \App\Services\Repositories\Todo\TodoUserDTO[] $watchers
 */
@endphp
@extends('todo-app::layouts.main')
@section('title', "ToDo: Задача проекта $project->name - $todo->title")
@section('content')
<div class="col-12">
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="row g-0">
                <nav class="col-lg-3 border-end">
                    @include('todo-app::projects.partials.nav', [
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
                                    <div class="small">
                                        <div class="fw-bold">Крайний срок:</div>
                                        <div class="ms-2">{{ $todo->deadline->format("d.m.Y") }}</div>
                                    </div>
                                    <div class="small">
                                        <div class="fw-bold">Статус:</div>
                                        <div class="ms-2">{{ $todo->status->name }}</div>
                                    </div>
                                    <div class="small">
                                        <div class="fw-bold">Постановщик:</div>
                                        <div class="ms-2">{{ $todo->author->name }}</div>
                                    </div>
                                    @if(!empty($responsibles[0]))
                                        <div class="small">
                                            <div class="fw-bold">Ответственный:</div>
                                            <div class="ms-2">{{ $responsibles[0]->name }}</div>
                                        </div>
                                    @endif
                                    @if(!empty($performers[0]))
                                        <div class="small">
                                            <div class="fw-bold ">Исполнитель:</div>
                                            <div class="ms-2">{{ $performers[0]->name }}</div>
                                        </div>
                                    @endif
                                    @if(!empty($watchers))
                                        <div class="">
                                            <small class="fw-bold d-block">Наблюдатели:</small>
                                            @foreach($watchers as $watcher)
                                            <div class="ms-2 small">{{ $watcher->name }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 mb-4 d-flex">
                                <span class="border border-secondary btn btn-sm text-muted"
                                    @click="showEdit=true"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Редактировать
                                </span>
                                <div class="ps-2 dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownUsers" data-bs-toggle="dropdown" aria-expanded="false">
                                        Делегировать
                                    </button>
                                    <ul x-data class="dropdown-menu" aria-labelledby="dropdownUsers">
                                        @foreach($projectUsers as $user)
                                            <li><span role="button" class="dropdown-item"
                                                @click="$store.todo.changeResponsible({{ $user->userId }})"
                                                >{{ $user->name }}</span></li>
                                        @endforeach
                                    </ul>
                                </div>
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

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('todo', {
            changeResponsible(user) {     
                let url = "{{ route('project.todos.user-role', ['projectId' => $project->projectId, 'todoId' => $todo->todoId]) }}"           

                axios.post(url, {
                    userId: user,
                    role: "{{ \App\Services\Repositories\Todo\TodoRoleEnum::PERFORMER }}"
                })
                .then((response) => {
                    if (response.data.success === true) {
                        window.location = "{{ route('project.todos.show', ['projectId' => $project->projectId, 'todoId' => $todo->todoId]) }}"
                    }
                })
                .catch((error) => {

                })
            },
        })
    })
</script>