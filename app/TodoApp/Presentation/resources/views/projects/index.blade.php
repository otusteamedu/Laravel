@php
/**
 * @var \App\TodoApp\Application\UseCases\Queries\Project\FetchForUser\Result $result
*/
@endphp
@extends('todo-app::layouts.main')
@section('title', 'ToDo: Мои проекты')
@section('content')
    @can('project.create')
        <div class="col-12 my-3 text-end">
            <a href="{{ route('projects.create') }}" class="btn btn-outline-primary">Добавить проект</a>
        </div>
    @endcan
    @if(empty($result->projectDTOs))
        <div class="alert alert-info">
            <h5>Упсс...</h5>
            <div>Вы еще не являетесь участником ни одного проекта.</div>
            @can('project.create')
                <div>Вы можете начать работу 
                    <a href="{{ route('projects.create') }}" 
                        class="link-offset-2 link-primary fw-bold">ДОБАВИВ</a>
                 собственный проект</div>
            @endcan
        </div>
    @endif
    @foreach($result->projectDTOs as $projectDTO)
        @can('project.invited', $projectDTO->projectId)
            @include('todo-app::projects.partials.project-card', [
                'projectId' => $projectDTO->projectId,
                'name' => $projectDTO->name,
                'description' => $projectDTO->description,
                'created' => \Illuminate\Support\Carbon::parse($projectDTO->created)->translatedFormat("j F Y")
            ])
        @endcan
    @endforeach
    
    @include('todo-app::projects.partials.delete-confirmation')
    @include('todo-app::projects.partials.left-confirmation')
    @include('todo-app::projects.partials.invite-reject-confirmation')
@endsection
