@extends('layouts.admin')

@section('content')
    <h1>Создание нового заказа</h1>

    <div class="row">
        @if ($errors->any())
            <div class="alert alert-danger mt-3 col-lg-8 col-xxl-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="row">
        <form class="mt-3 col-lg-8 col-xxl-6" action="{{ route('admin.orders.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="orderUser" class="form-label">Клиент</label>
                <select class="form-select" name="user_id" id="orderUser">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <div class="mb-1">Товары</div>
                <button type="button" class="btn btn-success" id="addProductButton">
                    Добавить товар
                </button>
                <div id="productContainer"></div>
            </div>

            <button type="submit" class="btn btn-primary">Создать заказ</button>
        </form>
    </div>

    <template id="productTemplate">
        <div class="input-group mt-2">
            <span class="input-group-text">Товар и количество</span>
            <select class="form-select" name="product_id[]">
                @foreach ($products as $product)
                    <option value="{{ $product->getId() }}">
                        {{ $product->getTitle() }}
                    </option>
                @endforeach
            </select>
            <input type="number" min="1" max="100" name="count[]" class="form-control">
        </div>
    </template>
@endsection