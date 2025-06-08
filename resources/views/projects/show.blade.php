@php
/**
 * @var \App\Services\Repositories\DTOs\ProjectDTO $project
*/
@endphp
@extends('layouts.main')
@section('title', "ToDo: Проект $project->name")

@section('content')
<div class="col-12">
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="row g-0">
                <nav class="col-lg-3 border-end">
                    @include('projects.partials.nav', [
                        'active'    => 'project',
                        'projectId' => $project->projectId,
                    ])
                </nav>
                <div x-data="{showEdit: false}" 
                    x-cloak
                    class="col-lg-9">
                    <div class="p-4" id="info">
                        <div x-show="!showEdit" class="mb-4">
                            <h4 class="mb-4">Информация</h4>
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between">
                                    <div><span class="fw-bold">{{ $project->name }}</span></div>
                                    @can('project.user.left', [$project->projectId, Auth::user()->id])
                                        <span class="text-muted">
                                            <i class="ps-2 fa-solid fa-ban text-danger"></i>
                                            <span
                                                role="button"
                                                class="fw-bold"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#project-jeft-confirmation" 
                                                x-data @click="$store.projects.left('{{ route('project.users.left', [$project->projectId, Auth::user()->id])}}', '{{ $project->name }}')"
                                                >Покинуть</span>
                                        </span>
                                    @endcan
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $project->description }}</p>
                                    <p class="card-text"><small class="text-muted">Создан: {{ $project->created->translatedFormat("j F Y") }}</small></p>
                                    {{-- ?TODO сводные данные по задачам --}}
                                    <p class="card-text d-flex flex-wrap justify-content-end gap-3">
                                        @can('project.update', $project->projectId)
                                            <span class="text-muted">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                                <button type="button"
                                                    @click="showEdit = true"
                                                    class="btn p-0"
                                                    >Редактировать</button>
                                            </span>
                                        @endcan
                                        @can('project.delete', $project->projectId)
                                            <span class="text-muted">
                                                <i class="fa-solid fa-trash-can"></i>
                                                <button
                                                    type="button"
                                                    class="btn p-0" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#project-delete-confirmation" 
                                                    data-bs-project-name="{{ $project->name }}"
                                                    data-bs-project-id="{{ $project->projectId }}"                    
                                                    >Удалить</button>
                                            </span>
                                        @endcan
                                    </p>
                                </div>
                            </div>
                        </div>
                        @can('project.update', $project->projectId)
                            <div  x-show="showEdit" class="pt-4">
                                <h4 class="mb-4">Редактирование проекта</h4>
                                <form method="POST" action="{{ route('projects.update', ['projectId' => $project->projectId]) }}" autocomplete="off">
                                    @csrf
                                    @method('put')
                                    @include('projects.partials.form-fields', ['name' => $project->name, 'description' => $project->description])
            
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

@include('projects.partials.delete-confirmation')
@include('projects.partials.left-confirmation')
