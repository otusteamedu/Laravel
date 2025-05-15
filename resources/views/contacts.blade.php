@extends('layouts.main')
@section('title', __('main.contacts'))

@section('content')
    <h1>{{ __('main.contacts') }}</h1>

    <div class="page-contacts__list">
        <div class="page-contacts__list-item">
            <div class="page-contacts__list-name">Телефон:</div>
            <div class="page-contacts__list-value"><a href="tel:88002000600" class="phone">88002000600</a></div>
        </div>
        <div class="page-contacts__list-item">
            <div class="page-contacts__list-name">E-mail:</div>
            <div class="page-contacts__list-value"><a href="mailto:info@yard-msk.ru">info@listo.ru</a></div>
        </div>
        <div class="page-contacts__list-item">
            <div class="page-contacts__list-name">Адрес:</div>
            <div class="page-contacts__list-value">Россия, г. Москва, БЦ «Башня на Набережной», Блок «С»</a></div>
        </div>
    </div>
@endsection
