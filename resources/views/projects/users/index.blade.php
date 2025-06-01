@php
/**
 * @var \App\Services\Repositories\DTOs\ProjectDTO $project
 * @var \App\Services\Repositories\DTOs\ProjectInvitedUserDTO[] $users
 */
@endphp
@extends('layouts.main')
@section('title', "ToDo: Участники проекта $project->name")

@section('content')
<div class="col-12">
    <div class="card border-0">
        <div class="card-body p-0">
            <div class="row g-0">
                <nav class="col-lg-3 border-end">
                    @include('projects.partials.nav', [
                        'active'    => 'users',
                        'projectId' => $project->id,
                    ])
                </nav>
                <div class="col-lg-9">
                    <div class="p-4" id="users">
                        <div class="mb-4">
                            <h4 class="mb-4">Участники проекта</h4>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Имя</th>
                                        <th scope="col">Роль</th>
                                        @can('project.user.manage', $project->id)
                                            <th scope="col" class="d-none d-md-table-cell">Пригашен</th>
                                            <th scope="col" class="d-none d-md-table-cell">С нами с</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <div>{{ $role }}</div>
                                            @endforeach
                                        </td>
                                        @can('project.user.manage', $project->id)
                                            <td class="d-none d-md-table-cell">{{ $user->invited->translatedFormat("j F Y") }}</td>
                                            <td class="d-none d-md-table-cell">{{ $user->joined->translatedFormat("j F Y") }}</td>
                                        @endcan
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
