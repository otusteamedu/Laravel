@extends('layouts.base')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Личный кабинет</h3>
                </div>
                
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <img src="{{ asset('img/user-avatar.png') }}" 
                                 alt="Аватар" 
                                 class="img-thumbnail rounded-circle"
                                 width="150">
                        </div>
                        <div class="col-md-8">
                            <h4>Иван Иванов</h4>
                            <p class="text-muted">Пользователь</p>
                            <p><i class="bi bi-envelope"></i> user@example.com</p>
                            <p><i class="bi bi-telephone"></i> +7 (123) 456-78-90</p>
                        </div>
                    </div>

                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-house"></i> Мои квартиры
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-receipt"></i> Мои квитанции
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="bi bi-gear"></i> Настройки профиля
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .list-group-item i {
        margin-right: 10px;
    }
</style>
@endpush