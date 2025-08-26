@extends ('layouts.main')
@use('App\Config')

@props(['$sheduleId', 'language_code', 'group_code', 'date', 'teacher', ])

@section('content')
    <div class="showblog">
        <h2>
            {{ $shedule->language_code }} ({{ Config::LANG[$shedule->language_code] }})
        </h2>
        <div class="showblog__date">
            <div>Created: {{ $shedule->created_at->format('d-m-Y H-i-s') }}</div>
            <div>Updated: {{ $shedule->updated_at->format('d-m-Y H-i-s') }}</div>
        </div>
        <p>Группа: {!! $shedule->group_code !!} ({{ Config::AGE[$shedule->group_code] }})</p>
        <p>Дни проведения: {!! $shedule->date !!}</p>
        <p>Преподаватель: {!! $shedule->teacher !!}</p>

        <div>
            <a href="{{ route('shedules.edit', ['shedule' => $shedule]) }}">Редактировать</a>
        </div>

    </div>
@endsection
