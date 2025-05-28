@extends('layouts.admin')

@section('content')
    <h1>Создание нового пользователя</h1>     

    <div class="row">
        @if ($errors->any())
        <div class="alert alert-danger mt-3 col-lg-8 col-xxl-6">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="row">
        <form class="mt-3 col-lg-8 col-xxl-6" action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="userName" class="form-label">Имя</label>
                <input type="text" class="form-control" name="name" id="userName" value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label for="userEmail" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="userEmail" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label for="userPassword" class="form-label">Пароль</label>
                <input type="password" class="form-control" name="password" id="userPassword">
            </div>

            <div class="mb-3">
                <label for="userPasswordConfirm" class="form-label">Подтверждение пароля</label>
                <input type="password" class="form-control" name="password_confirmation" id="userPasswordConfirm">
            </div>

            <div class="mb-3">
                <label for="userRole" class="form-label">Роль</label>
                <select class="form-select" name="role_id" id="userRole">
                    @foreach ($roles as $role)
                    <option value="{{ $role->getId() }}" @selected(old('role_id') == $role->getId())>
                        {{ $role->getTitle() }}   
                    </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection