@extends('layouts.base')

@section('content')
<form method="POST" action="{{ route('tariffs.destroy', ['id' => $tariff->id]) }}" class="modal-content">
    @csrf
    @method('DELETE')

    <div class="modal-content">
        <div class="modal-header text-center custom-modal-header">
            <h5 class="modal-title w-100" style="font-size: 14px; color: #FFFFFF; font-family: Segoe UI;">
                {{ $title ?? 'Подтверждение действия' }}
            </h5>
        </div>

        <div class="modal-body">
            <div class="row mb-3">
                <div class="col text-center">
                    <p>Вы действительно хотите удалить тариф</p>
                    <p><strong>"{{ $tariff_name }}"</strong>?</p>
                </div>
            </div>

            <br>

            <div class="row mb-3">
                <div class="col text-center">
                    <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                        <a href="{{ route('tariffs.index') }}" class="btn-segoe">Отменить</a>
                        <button type="submit" class="btn-segoe-primary">Удалить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
