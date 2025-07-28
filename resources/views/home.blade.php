@extends('layouts.base')

@section('content')
<div class="container">
    <h2 class="text-center my-5">Добро пожаловать в ТСЖ "Радуга"</h2>

    <div class="mb-3">
        <label for="filter" class="form-label">Фильтр:</label>
        <select id="filter" class="form-select">
            <option value="">Все</option>
            <option value="balance_end_gt_6000">Сальдо (конец) &gt; 6000</option>
        </select>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>№</th>
                <th>Собственник</th>
                <th>Кол-во прожив.</th>
                <th>Общ. площ.</th>
                <th>Содерж. помещения</th>
                <th>Начислено</th>
                <th>Перерасчёт</th>
                <th>Сальдо (начало)</th>
                <th>Сальдо (конец)</th>
                <th>Оплачено</th>
                <th>Пеня</th>
                <th>Итого</th>
            </tr>
        </thead>
        <tbody>
            @foreach($apartments as $apartment)
                <tr onclick="location.href='/update/{{ $apartment->serial_number }}';" style="cursor:pointer;">
                    <td>{{ $apartment->serial_number }}</td>
                    <td>{{ $apartment->owner }}</td>
                    <td>{{ $apartment->details->first()->lived_qt ?? '' }}</td>
                    <td>{{ $apartment->details->first()->total_area ?? '' }}</td>
                    <td>{{ number_format($apartment->fees->first()->maintenance ?? 0, 2, ',', ' ') }}</td>
                    <td>{{ number_format($apartment->fees->first()->accrued_expenses ?? 0, 2, ',', ' ') }}</td>
                    <td>{{ number_format($apartment->fees->first()->recalculation ?? 0, 2, ',', ' ') }}</td>
                    <td>{{ number_format($apartment->fees->first()->balance_start ?? 0, 2, ',', ' ') }}</td>
                    <td>{{ number_format($apartment->fees->first()->balance_end ?? 0, 2, ',', ' ') }}</td>
                    <td>{{ number_format($apartment->fees->first()->paid ?? 0, 2, ',', ' ') }}</td>
                    <td>{{ number_format($apartment->fees->first()->fine ?? 0, 2, ',', ' ') }}</td>
                    <td>{{ number_format($apartment->fees->first()->total ?? 0, 2, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    const filterSelect = document.getElementById('filter');
    const urlParams = new URLSearchParams(window.location.search);
    const currentFilter = urlParams.get('filter');
    if (currentFilter) filterSelect.value = currentFilter;

    filterSelect.addEventListener('change', function () {
        const selectedValue = this.value;
        let newUrl = window.location.origin + window.location.pathname;
        if (selectedValue) {
            newUrl += '?filter=' + selectedValue;
        }
        window.location.href = newUrl;
    });
</script>
@endsection
