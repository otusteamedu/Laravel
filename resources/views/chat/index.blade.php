@extends('layouts.app')

@section('content')
    <div class="container-lg">
        <div class="p-3 bg-body-tertiary rounded-3">
            <h1 class="display-6 fw-bold text-center">Чат</h1>
        </div>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div>
                    @foreach ($messages as $message)
                        <div>
                            <p class="fw-bold">{{ $message->getUser()->name }},
                                {{ $message->getCreatedAt()->format('d.m.Y H:i') }}</p>
                            <p>{{ $message->getContent() }}</p>
                        </div>
                    @endforeach
                </div>

                @guest
                <div>
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        Авторизуйтесь, чтобы написать сообщение
                    </a>
                </div>
                @endguest

                @auth
                <form>
                    <div class="mb-3">
                        <textarea class="form-control" rows="3" placeholder="Текст сообщения"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить сообщение</button>
                </form>
                @endauth
            </div>
        </div>
    </div>
@endsection