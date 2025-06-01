@php
/**
 * @var \App\Services\Repositories\DTOs\ProjectDTO $project
 * @var \App\Services\Repositories\DTOs\UserDTO[] $users
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
                        'projectId' => $project->id,
                    ])
                </nav>
                <div class="col-lg-9">
                    <div class="p-4" id="info">
                        <div class="mb-4">
                            <h4 class="mb-4">Информация</h4>
                            <div class="card mb-3">
                                <div class="card-header fw-bold">
                                    {{ $project->name }}
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $project->description }}</p>
                                    <p class="card-text"><small class="text-muted">Создан: {{ $project->created->translatedFormat("j F Y") }}</small></p>
                                    {{-- ?TODO сводные данные по задачам --}}
                                    <p class="card-text d-flex flex-wrap justify-content-end gap-3">
                                        <span class="text-muted">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <a href="{{ route('projects.edit', ['projectId' => $project->id]) }}"
                                                class="btn p-0"
                                                >Редактировать</a>
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header fw-bold">
                                    Участники проекта
                                </div>
                                <div class="card-body">
                                    @foreach($users as $user)
                                        {{ $user->name }}  {{ $user->roles[0] }}
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('projects.partials.delete-confirmation')
