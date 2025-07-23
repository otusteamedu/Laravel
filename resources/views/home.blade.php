@extends('layouts.base')

@section('content')
<div class="container">
    <h2 class="text-center my-5">Добро пожаловать в ТСЖ "Радуга"</h2>
    
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <p class="lead">Здесь будет основное содержимое главной страницы.</p>
                    <p>Вы можете добавить:</p>
                    <ul>
                        <li>Краткую информацию о ТСЖ</li>
                        <li>Важные объявления</li>
                        <li>Ссылки на основные разделы</li>
                    </ul>

                    <!-- Кнопка для вызова перерасчёта -->
                    <form method="GET" action="{{ route('calculate_fees') }}">
                        <button type="submit" class="btn btn-primary">Перерасчёт начислений</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
