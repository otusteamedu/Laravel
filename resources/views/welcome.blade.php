@extends('layouts.app')

@section('content')
    <!-- Hero секция -->
    <section class="py-5 bg-light text-center">
        <div class="container">
            <h1 class="display-4">{{ __('pages.welcome.title') }}</h1>
            <p class="lead">{{ __('pages.welcome.sub_title') }}</p>
            <a href="#" class="btn btn-primary btn-lg mt-3">{{__('main.services_not_found')}}</a>
        </div>
    </section>

    <!-- Карточки -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                @forelse($services as $serviceName)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm">
                            <img src="https://jjji.ru/400x225" class="card-img-top" alt="{{$sserviceName ?? __('main.not_name')}}">
                            <div class="card-body">
                                <h5 class="card-title">{{$serviceName ?? __('main.not_name')}}</h5>
                                <p class="card-text">Краткое описание услуги или продукта. Всё, что нужно знать клиенту.</p>
                                <a href="#" class="btn btn-outline-primary">{{ __('main.more_details') }}</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <h3 class="display-7">{{__('main.services_not_found')}}</h3>
                @endforelse
            </div>
        </div>
    </section>
@endsection
