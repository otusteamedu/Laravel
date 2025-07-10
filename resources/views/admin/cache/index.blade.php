@extends('layouts.admin')

@section('title', 'Управление кэшем')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Управление кэшем</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Общие операции</h4>
                            <div class="mb-3">
                                <form method="POST" action="{{ route('admin.cache.warmup') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-fire"></i> Прогреть весь кэш
                                    </button>
                                </form>
                            </div>
                            <div class="mb-3">
                                <form method="POST" action="{{ route('admin.cache.clear') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Вы уверены, что хотите очистить весь кэш?')">
                                        <i class="fas fa-trash"></i> Очистить весь кэш
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4>Выборочный прогрев</h4>
                            <div class="mb-2">
                                <form method="POST" action="{{ route('admin.cache.warmup.tasks') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-tasks"></i> Прогреть кэш задач
                                    </button>
                                </form>
                            </div>
                            <div class="mb-2">
                                <form method="POST" action="{{ route('admin.cache.warmup.categories') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-folder"></i> Прогреть кэш категорий
                                    </button>
                                </form>
                            </div>
                            <div class="mb-2">
                                <form method="POST" action="{{ route('admin.cache.warmup.users') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-users"></i> Прогреть кэш пользователей
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-lg {
    padding: 15px 30px;
    font-size: 1.2em;
}

.card-header {
    background-color: #343a40;
    color: white;
}

.alert ul {
    margin-bottom: 0;
}

.alert li {
    margin-bottom: 5px;
}
</style>
@endsection
