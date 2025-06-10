@php
/**
 * @var \App\Services\Repositories\DTOs\ProjectDTO $project
 * @var \App\Services\Repositories\DTOs\TodoStatusDTO[] $statuses
*/
@endphp
@extends('layouts.main')
@section('title', "ToDo: Статусы задач для проекта $project->name")
@section('content')
<div class="col-12">
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="row g-0">
                <nav class="col-lg-3 border-end">
                    @include('projects.partials.nav', [
                        'active'    => 'statuses',
                        'projectId' => $project->projectId,
                    ])
                </nav>
                <div class="col-lg-9">
                    <div class="p-4" id="statuses">
                        <div class="mb-4">
                            <h4 class="mb-4">Статусы для задач проектa {{ $project->name }}</h4>
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
                                @foreach($statuses as $status)
                                    @include('todos.statuses.status-card', [
                                        'projectId' => $project->projectId,
                                        'statusId'  => $status->statusId,
                                        'name'      => $status->name,
                                        'color'     => $status->color,
                                        'sort'      => $status->sort
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

@include('todos.statuses.delete-confirmation', ['projectId' => $project->projectId])

<script>
    document.addEventListener('alpine:init', () => {

        Alpine.store('todoStatuses', {
            showForm: {{  empty(json_decode($errors)) ? 'false' : 'true' }},
            title: 'Добавить статус',
            projectId: {{ $project->projectId }},
            statusId:  "{{ old('id', null) }}",
            name: "{{ old('name', '') }}",
            sort: {{ old('sort', 100) }},
            color: "{{ old('color', '#f8f9fa') }}",
            action: '',
            data: {statuses: @json($statuses)},
 
            init() {
                if (this.statusId !== null) {
                    this.title = 'Редактировать статус'
                } 
            },

            formShow(data = null) {
                this.showForm = true

                if (data !== null) {
                    this.name     = data.name
                    this.color    = data.color
                    this.sort     = data.sort
                    this.statusId = data.statusId
                    this.action   = "{{ route('project.todostatuses.update', ['projectId' => $project->projectId]) }}"
                } else {
                    this.action   = "{{ route('project.todostatuses.store', ['projectId' => $project->projectId]) }}"
                }
            },

            formHide() {
                this.showForm = false

                this.formClear()
            },

            formClear() {
                this.showForm  = false
                this.statusId  = null
                this.name      = ''
                this.sort      = 100
                this.color     = 'f8f9fa'
                this.action    = ''
            },

            confirmDelete(data) {
                this.name     = data.name
                this.statusId = data.statusId
            },
        })

    })
</script>