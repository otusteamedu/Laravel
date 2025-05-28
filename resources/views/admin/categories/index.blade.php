@extends('layouts.admin')

@section('content')
    <h1>Список категорий</h1>

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
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Создать категорию</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Название</th>
                <th scope="col">Создана</th>
                <th scope="col">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->getId() }}</td>
                    <td>{{ $category->getTitle() }}</td>
                    <td>{{ $category->getCreatedAt()->format('d.m.Y H:i') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.categories.show', $category->getId()) }}" class="btn btn-success">Смотреть</a>
                            <a href="{{ route('admin.categories.edit', $category->getId()) }}" class="btn btn-warning">Редактировать</a>

                            <button type="button" class="btn btn-danger" data-bs-toggle="collapse" data-bs-target="#collapse{{ $category->getId() }}" 
                                aria-expanded="false" aria-controls="collapse{{ $category->getId() }}">
                                Удалить
                            </button>
                        </div>
                        <div class="collapse mt-2" id="collapse{{ $category->getId() }}">
                            <div>
                                Вы уверены?
                            </div>
                            <div>
                                <form action="{{ route('admin.categories.destroy', $category->getId()) }}" method="POST">
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
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection