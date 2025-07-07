@php
/**
 * @var \App\TodoApp\Application\DTOs\ProjectDTO $project
*/
@endphp
@extends('todo-app::layouts.main')
@section('title', "ToDo: Проект $projectDTO->name")

@section('content')
<div class="col-12">
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="row g-0">
                <nav class="col-lg-3 border-end">
                    @include('todo-app::projects.partials.nav', [
                        'active'    => 'project',
                        'projectId' => $projectDTO->projectId,
                    ])
                </nav>
                <div x-data="{showEdit: @if(session('error')) true @else false @endif}" 
                    x-cloak
                    class="col-lg-9">
                    <div class="p-4" id="info">
                        <div x-show="!showEdit" class="mb-4">
                            <h4 class="mb-4">Информация</h4>
                            <div id="project-view-{{ $projectDTO->projectId }}" class="project-card card mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div><span class="fw-bold">{{ $projectDTO->name }}</span></div>
                                    @can('project.user.left', [$projectDTO->projectId, Auth::user()->id])
                                        <span class="text-muted">
                                            <i class="ps-2 fa-solid fa-ban text-danger"></i>
                                            <span role="button"
                                                id="project-left-btn-{{ $projectDTO->projectId }}"
                                                class="fw-bold"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#projectDTO-jeft-confirmation" 
                                                x-data @click="$store.projects.left('{{ route('project.users.left', [$projectDTO->projectId, Auth::user()->id])}}', '{{ $projectDTO->name }}')"
                                                >Покинуть</span>
                                        </span>
                                    @endcan
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $projectDTO->description }}</p>
                                    <p class="card-text"><small class="text-muted">Создан: {{ \Illuminate\Support\Carbon::parse($projectDTO->created)->translatedFormat("j F Y") }}</small></p>
                                    {{-- ?TODO сводные данные по задачам --}}
                                    <p class="card-text d-flex flex-wrap justify-content-end gap-3">
                                        @can('project.update', $projectDTO->projectId)
                                            <span class="text-muted">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <button type="button"
                                                    @click="showEdit = true"
                                                    class="btn p-0"
                                                    >Редактировать</button>
                                            </span>
                                        @endcan
                                        @can('project.delete', $projectDTO->projectId)
                                            <span class="text-muted">
                                                <i class="fa-solid fa-trash-can"></i>
                                                <button
                                                    type="button"
                                                    class="btn p-0" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#project-delete-confirmation" 
                                                    data-bs-project-name="{{ $projectDTO->name }}"
                                                    data-bs-project-id="{{ $projectDTO->projectId }}"                    
                                                    >Удалить</button>
                                            </span>
                                        @endcan
                                    </p>
                                </div>
                            </div>
                        </div>
                        @can('project.update', $projectDTO->projectId)
                            <div x-show="showEdit" class="pt-4">
                                <h4 class="mb-4">Редактирование проекта</h4>
                                <form id="project-edit-form-{{ $projectDTO->projectId }}" method="POST" action="{{ route('projects.update', ['projectId' => $projectDTO->projectId]) }}" autocomplete="off">
                                    @csrf
                                    @method('put')
                                    @include('todo-app::projects.partials.form-fields', ['name' => $projectDTO->name, 'description' => $projectDTO->description])
            
                                    <div class="col-12 my-2 text-end">
                                        <button class="btn btn-outline-primary">Сохранить</button>
                                    </div>
                                </form>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('todo-app::projects.partials.delete-confirmation')
@include('todo-app::projects.partials.left-confirmation')
