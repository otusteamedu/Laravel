@extends('layouts.admin')

@section('content')
    <h1>Список пользователей</h1>

    <div class="row">
        @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="my-4">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Создать нового пользователя</a>
        <a href="{{ route('admin.users.export') }}" class="btn btn-success">Экспорт в Excel</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">
                    #
                    <a href="{{ route(
                        'admin.users.index', 
                        array_merge(request()->query(), ['sort' => 'id', 'direction' => $direction === 'asc' ? 'desc' : 'asc'])
                        ) }}" 
                        class="link-underline-light">
                        &#8645;
                    </a>
                </th>
                <th scope="col">
                    Имя
                    <a href="{{ route(
                        'admin.users.index', 
                        array_merge(request()->query(), ['sort' => 'name', 'direction' => $direction === 'asc' ? 'desc' : 'asc'])
                        ) }}" 
                        class="link-underline-light">
                        &#8645;
                    </a>
                </th>
                <th scope="col">
                    Email
                    <a href="{{ route(
                        'admin.users.index', 
                        array_merge(request()->query(), ['sort' => 'email', 'direction' => $direction === 'asc' ? 'desc' : 'asc'])
                        ) }}" 
                        class="link-underline-light">
                        &#8645;
                    </a>
                </th>
                <th scope="col">
                    Создан
                    <a href="{{ route(
                        'admin.users.index', 
                        array_merge(request()->query(), ['sort' => 'created_at', 'direction' => $direction === 'asc' ? 'desc' : 'asc'])
                        ) }}" 
                        class="link-underline-light">
                        &#8645;
                    </a>
                </th>
                @canany(['viewAny', 'update', 'delete'], App\Models\User::class)
                <th scope="col">Действия</th>
                @endcan
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                    @canany(['viewAny', 'update', 'delete'], App\Models\User::class)
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-success">Смотреть</a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">Редактировать</a>

                            <button type="button" class="btn btn-danger" data-bs-toggle="collapse" data-bs-target="#collapse{{ $user->id }}" 
                                aria-expanded="false" aria-controls="collapse{{ $user->id }}">
                                Удалить
                            </button>
                        </div>
                        <div class="collapse mt-2" id="collapse{{ $user->id }}">
                            <div>
                                Вы уверены?
                            </div>
                            <div>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Да, удалить</button>
                                    <button type="button" class="btn btn-secondary" 
                                        onclick="this.closest('.collapse').previousElementSibling.querySelector('[data-bs-target]').click()">
                                        Отмена
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                    @endcan
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        {{ $users->links() }}
    </div>
@endsection