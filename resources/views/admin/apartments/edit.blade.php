@extends('layouts.app')

@section('title', 'Редактировать квартиру')

@section('content')
    <h1 class="mb-4">Редактировать квартиру #{{ $apartment->id }}</h1>

    <form method="POST" action="{{ route('admin.apartments.update', $apartment) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Владелец</label>
            <input type="text" name="owner" class="form-control" value="{{ old('owner', $apartment->owner) }}">
            @error('owner')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Серийный номер</label>
            <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $apartment->serial_number) }}">
            @error('serial_number')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{ route('admin.apartments.index') }}" class="btn btn-secondary">Назад</a>
    </form>
@endsection
