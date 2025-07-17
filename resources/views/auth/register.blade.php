@extends('layouts.base')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Регистрация</h4>
                </div>
                
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">ФИО</label>
                            <input type="text" class="form-control" id="name" placeholder="Иванов Иван Иванович">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="example@mail.ru">
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Телефон</label>
                            <input type="tel" class="form-control" id="phone" placeholder="+7 (999) 123-45-67">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Пароль</label>
                            <input type="password" class="form-control" id="password">
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Повторите пароль</label>
                            <input type="password" class="form-control" id="password_confirmation">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                        </div>
                    </form>
                    
                    <div class="mt-3 text-center">
                        Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .card {
        border-radius: 10px;
    }
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    .form-control:focus {
        border-color: #5600a8;
        box-shadow: 0 0 0 0.25rem rgba(89, 0, 168, 0.25);
    }
</style>
@endpush