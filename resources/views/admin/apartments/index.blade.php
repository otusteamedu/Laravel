@extends('layouts.app')

@section('title', 'Квартиры')

@section('content')
    <h1 class="mb-4">Список квартир</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.apartments.create') }}" class="btn btn-primary mb-3">Добавить новую квартиру</a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Владелец</th>
                <th>Серийный номер</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            @foreach($apartments as $apartment)
                <tr>
                    <td>{{ $apartment->id }}</td>
                    <td>{{ $apartment->owner }}</td>
                    <td>{{ $apartment->serial_number }}</td>
                    <td>
                        <a href="{{ route('admin.apartments.edit', $apartment) }}" class="btn btn-sm btn-primary">
                            Редактировать
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
