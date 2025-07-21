@extends('layouts.app')

@section('title', 'Список настроек')

@section('content')
    <h1 class="mb-4">Список настроек</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Месяц</th>
                <th>Срок оплаты</th>
                <th>Дата списания</th>
                <th>Счёт</th>
                <th>Оплатить до</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            @foreach($settings as $setting)
                <tr>
                    <td>{{ $setting->id }}</td>
                    <td>{{ $setting->month_name }}</td>
                    <td>{{ $setting->month_to_pay }}</td>
                    <td>{{ $setting->month_to_date }}</td>
                    <td>{{ $setting->bill }}</td>
                    <td>{{ $setting->pay_up_to }}</td>
                    <td>
                        <a href="{{ route('admin.settings.edit', $setting) }}" class="btn btn-sm btn-primary">Редактировать</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
