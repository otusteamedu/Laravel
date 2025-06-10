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
                        'projectId' => $project->projectId,
                    ])
                </nav>
                <div class="col-lg-9">
                    <div class="p-4" id="users">
                        @can('project.user.manage', $project->projectId)
                            <div x-show="showEdit" class="pt-4 col-lg-6">
                                <h4 class="mb-4">Пригласить пользователя</h4>
                                <form method="POST" action="{{ route('project.users.invite', ['projectId' => $project->projectId]) }}" autocomplete="off">
                                    @csrf
                                    <div class="input-group mb-4">
                                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                        <input name="email"
                                            type="email"
                                            autocomplete="off"
                                            value="{{ old('email', '') }}"
                                            @class([
                                            'form-control',
                                            'is-invalid' => !empty($errors->get('email'))
                                        ]) 
                                        placeholder="Email адрес" required autocomplete="username">
                                        <button class="btn btn-primary rounded-end">Пригласить</button>
                                        <x-invalid-feedback :errors="$errors->get('email')"/>
                                    </div>
                                </form>
                            </div>
                        @endcan
                        <div class="mb-4">
                            <h4 class="mb-4">Участники проекта</h4>
                            <table class="project-user-table table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Имя</th>
                                        <th scope="col">Роль</th>
                                        @can('project.user.manage', $project->projectId)
                                            <th scope="col" class="d-none d-md-table-cell">Пригашен</th>
                                            <th scope="col" class="d-none d-md-table-cell">С нами с</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr id="project-member-list-{{ $user->userId }}">
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <div>{{ $role }}</div>
                                            @endforeach
                                        </td>
                                        @can('project.user.manage', $project->projectId)
                                            <td class="d-none d-md-table-cell">{{ $user->invited->translatedFormat("j F Y") }}</td>
                                            <td class="d-none d-md-table-cell">{{ $user->joined?->translatedFormat("j F Y") }}</td>
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
