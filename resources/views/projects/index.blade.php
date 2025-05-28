@php
/**
 * @var \App\Services\UseCases\Queries\Project\FetchForUser\Result $result
*/
@endphp
@extends('layouts.main')
@section('title', 'ToDo: Мои проекты')
@section('content')
    <div class="col-12 my-3 text-end">
        <a href="{{ route('projects.create') }}" class="btn btn-outline-primary">Добавить проект</a>
    </div>
    @foreach($result->ptojectDTOs as $project)
        @include('projects.partials.project-card', [
            'projectId' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'created' => $project->created->translatedFormat("j F Y")
        ])
    @endforeach
    
    @include('projects.partials.delete-confirmation')
@endsection
