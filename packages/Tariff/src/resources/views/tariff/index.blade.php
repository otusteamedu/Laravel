@extends('layouts.base')

@section('title', 'Тарифы')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-sm-6 text-start">
            <h1 class="special2">Тарифы</h1>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-sm-6">
            <a class="btn-segoe-main"
               hx-get="{{ route('tariffs.create') }}"
               hx-target="#dialog">Добавить новый тариф</a>
        </div>
    </div>

    <br>

    <table class="apartment-table">
        <colgroup>
            <col style="width: 5%;">
            <col style="width: 25%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 7%;">
            <col style="width: 20%;">
        </colgroup>
        <thead class="apartment-table">
            <tr>
                <th class="apartment-header">№</th>
                <th class="apartment-header">Наименование</th>
                <th class="apartment-header">Содержание жилья</th>
                <th class="apartment-header">Отопление (ГКал)</th>
                <th class="apartment-header">Отопление (рубли)</th>
                <th class="apartment-header">Горячая вода</th>
                <th class="apartment-header">Действие</th>
            </tr>
        </thead>
        <tbody hx-trigger="load, tariffListChanged from:body" hx-get="{{ route('tariffs.index') }}" hx-target="this">
            @include('tariff::tariff.partials.tbody', ['tariffs' => $tariffs])
        </tbody>
    </table>
</div>


{{-- Модальное окно редактирования --}}
<div id="modal" class="modal fade">
    <div id="dialog" class="modal-dialog modal-dialog-centered modal-lg" hx-target="this"></div>
</div>

{{-- Модальное окно подтверждения --}}
<div id="modal_conf" class="modal fade">
    <div id="confirmation" class="modal-dialog modal-dialog-centered" hx-target="this"></div>
</div>


{{-- Тост сообщение --}}
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div id="toast-body" class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

{{-- Кнопка вверх --}}
<div class="back-to-top"></div>

<script>
    var backToTopButton = document.querySelector('.back-to-top');

    window.addEventListener('scroll', function() {
        backToTopButton.style.display = (window.pageYOffset > 100) ? 'block' : 'none';
    });

    backToTopButton.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>

@endsection