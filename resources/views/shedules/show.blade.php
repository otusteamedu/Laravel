@extends ('layouts.main')

@props(['$sheduleId', 'language_code', 'group_code', 'date', 'teacher', ])

@php
/**
 * В этом шаблоне используется модель.
 * @var \App\Models\Shedule $shedule
 */
@endphp

@section('content')
    <div class="showblog">
        <h2>
            {{ $shedule->language_code }}
        </h2>
        <div class="showblog__date">
            <div>Created: {{ $shedule->created_at->format('d-m-Y H-i-s') }}</div>
            <div>Updated: {{ $shedule->updated_at->format('d-m-Y H-i-s') }}</div>
        </div>
        <p>Группа: {!! $shedule->group_code !!}</p>
        <p>Дни проведения: {!! $shedule->date !!}</p>
        <p>Преподаватель: {!! $shedule->teacher !!}</p>

        <div>
            <a href="{{ route('shedules.edit', ['shedule' => $shedule]) }}">Edit</a>
        </div>

    </div>
@endsection
