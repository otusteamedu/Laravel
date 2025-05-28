@php
/**
 * @var \App\Services\UseCases\Queries\Project\FetchWithRelations\ProjectDTO $project
 * @var \App\Services\UseCases\Queries\Project\FetchWithRelations\TodoStatusDTO[] $statuses
*/
@endphp
@extends('layouts.main')
@section('title', 'ToDo: Проект')

@section('content')
<div class="col-12">
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="row g-0">
                <nav class="col-lg-3 border-end">
                    @include('projects.partials.nav')`
                </nav>
                <div class="col-lg-9 tab-content">
                    @include('projects.partials.info-tab', [
                        'projectId' => $project->id,
                        'name' => $project->name,
                        'description' => $project->description,
                        'created' => $project->created->translatedFormat("j F Y")
                    ])
                    @include('projects.partials.statuses-tab', [
                        'statuses'  => $statuses,
                        'projectId' => $project->id,
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

