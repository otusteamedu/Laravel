@extends('layouts.base')

@section('css')
    <link href="{{ asset('css/apartment_base.css') }}" rel="stylesheet">
@endsection

@section('apartment_header')
<div class="container">
    <br>
    <div class="row">
        <div class="col-sm-6 text-start">
            <h1 class="special2">Квартиры</h1>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-6">
            <a class="btn-segoe-main" href="{{ route('apartments.update_fees') }}">Перерасчёт</a>
            <a class="btn-segoe-main" href="{{ route('apartments.generate_excel') }}?filter={{ request()->filter }}">Excel</a>
            <a class="btn-segoe-main" href="{{ route('apartments.receipts') }}">Квитанции</a>
            <a class="btn-segoe-main" href="{{ route('apartments.generate_txt') }}">Для клиент-банка</a>
            <a class="btn-segoe-main" href="{{ route('apartments.generate_totals') }}">Итоги</a>
        </div>
        <div class="col-sm-6 text-end">
            <form method="get" action="{{ route('apartments.index') }}">
                <label for="filter">Выберите фильтр:</label>
                <select name="filter" id="filter">
                    <option value="">Все записи</option>
                    <option value="balance_end_gt_6000" {{ request()->filter == 'balance_end_gt_6000' ? 'selected' : '' }}>Должники (сальдо конец > 6000)</option>
                </select>
            </form>
        </div>
    </div>
</div>
<br>
@endsection