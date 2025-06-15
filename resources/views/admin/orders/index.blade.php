@extends('layouts.admin')

@section('content')
    <h1>Список заказов</h1>

    <div class="row">
        @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="my-4">
        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">Создать заказ</a>
        <a href="{{ route('admin.orders.export') }}" class="btn btn-success">Экспорт в Excel</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">
                    #
                    <a href="{{ route(
                        'admin.orders.index', 
                        array_merge(request()->query(), ['sort' => 'id', 'direction' => $direction === 'asc' ? 'desc' : 'asc'])
                        ) }}" 
                        class="link-underline-light">
                        &#8645;
                    </a>
                </th>
                <th scope="col">
                    Клиент
                    <a href="{{ route(
                        'admin.orders.index', 
                        array_merge(request()->query(), ['sort' => 'user', 'direction' => $direction === 'asc' ? 'desc' : 'asc'])
                        ) }}" 
                        class="link-underline-light">
                        &#8645;
                    </a>
                </th>
                <th scope="col">
                    Создан
                    <a href="{{ route(
                        'admin.orders.index', 
                        array_merge(request()->query(), ['sort' => 'created_at', 'direction' => $direction === 'asc' ? 'desc' : 'asc'])
                        ) }}" 
                        class="link-underline-light">
                        &#8645;
                    </a>
                </th>
                <th scope="col">Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->getId() }}</td>
                    <td>{{ $order->getUser()->name }}</td>
                    <td>{{ $order->getCreatedAt()->format('d.m.Y H:i') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.orders.show', $order->getId()) }}" class="btn btn-success">Смотреть</a>
                            <a href="{{ route('admin.orders.edit', $order->getId()) }}" class="btn btn-warning">Редактировать</a>

                            <button type="button" class="btn btn-danger" data-bs-toggle="collapse" data-bs-target="#collapse{{ $order->getId() }}" 
                                aria-expanded="false" aria-controls="collapse{{ $order->getId() }}">
                                Удалить
                            </button>
                        </div>
                        <div class="collapse mt-2" id="collapse{{ $order->getId() }}">
                            <div>
                                Вы уверены?
                            </div>
                            <div>
                                <form action="{{ route('admin.orders.destroy', $order->getId()) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Да, удалить</button>
                                    <button type="button" class="btn btn-secondary" 
                                        onclick="this.closest('.collapse').previousElementSibling.querySelector('[data-bs-target]').click()">
                                        Отмена
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        {{ $orders->links() }}
    </div>
@endsection