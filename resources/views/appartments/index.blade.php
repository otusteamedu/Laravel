@extends('apartment.base')

@section('content')
<div class="container">
    <table class="apartment-table">
        <colgroup>
            <col style="width: 3%;">
            <col style="width: 22%;">
            <col style="width: 7%;">
            <!-- Остальные колонки -->
        </colgroup>
        <thead>
            <tr>
                <th class="apartment-header">№</th>
                <th class="apartment-header">Собственник</th>
                <!-- Остальные заголовки -->
            </tr>
        </thead>
        <tbody>
            @foreach ($apartments as $apartment)
            <tr onclick="window.location='{{ route('apartments.update', $apartment->serialNumber) }}'" style="cursor:pointer;">
                <td><span class="apartment-nomer">{{ $apartment->serialNumber }}</span></td>
                <td><span class="apartment-owner">{{ $apartment->owner }}</span></td>
                <!-- Остальные данные -->
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="back-to-top"></div>

<script>
    // Кнопка "Наверх"
    const backToTopButton = document.querySelector('.back-to-top');
    window.addEventListener('scroll', () => {
        backToTopButton.style.display = window.pageYOffset > 100 ? 'block' : 'none';
    });
    backToTopButton.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Фильтр
    document.getElementById('filter').addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('filter', this.value);
        window.location.href = url.toString();
    });
</script>
@endsection