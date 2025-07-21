@extends('layouts.app')

@section('title', 'Редактирование настройки')

@section('content')
    <h1 class="mb-4">Редактировать настройку #{{ $setting->id }}</h1>

    <form method="POST" action="{{ route('admin.settings.update', $setting) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Месяц</label>
            <input type="text" name="month_name" class="form-control" value="{{ old('month_name', $setting->month_name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Срок оплаты</label>
            <input type="text" name="month_to_pay" class="form-control" value="{{ old('month_to_pay', $setting->month_to_pay) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Дата списания</label>
            <input type="text" name="month_to_date" class="form-control" value="{{ old('month_to_date', $setting->month_to_date) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Счёт</label>
            <input type="text" name="bill" class="form-control" value="{{ old('bill', $setting->bill) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Оплатить до</label>
            <input type="text" name="pay_up_to" class="form-control" value="{{ old('pay_up_to', $setting->pay_up_to) }}">
        </div>

        <button type="submit" class="btn btn-success">Сохранить</button>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Назад</a>
    </form>
@endsection
