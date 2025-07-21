@extends('layouts.app')

@section('title', 'Добавить новую квартиру')

@section('content')
    <h1 class="mb-4">Добавить новую квартиру</h1>

    <form method="POST" action="{{ route('admin.apartments.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Владелец</label>
            <input type="text" name="owner" class="form-control" value="{{ old('owner') }}">
            @error('owner')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Серийный номер</label>
            <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number') }}">
            @error('serial_number')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Создать</button>
        <a href="{{ route('admin.apartments.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
@endsection
