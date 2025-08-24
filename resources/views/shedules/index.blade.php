@php
    use App\Models\Shedule;
    use App\Models\User;

@endphp

@extends ('layouts.main')

@php
/**
 * @var Shedule[] $shedules
 */
@endphp

@section('content')
    <div class="flex items-center justify-between">
        <h1>Расписание занятий</h1>
        <button class="mb-40">
            <a href="{{ route('shedules.create') }}">Добавить новую запись</a>
        </button>
    </div>
    <div class="newscont">
         <div class="newscont__id">
            №
        </div>
        <div class="newscont__title">
            Язык
        </div>
                <div class="newscont__title">
            Группа
        </div>
                <div class="newscont__title">
            Дни проведения
        </div>
                <div class="newscont__title">
            Преподаватель
        </div>
        <div class="newscont__date">
            Дата создания
        </div>
        <div class="newscont__action">
            Действие
        </div>
        <div class="newscont__author">
            Кто создал
        </div>
    </div>

    @foreach($shedules as $shedule)
            @include('shedules.components.shedule', [
                'sheduleId' => $shedule->id,
                'date' => $shedule->created_at->format('d-m-Y H-i-s'),
                'language_code' => $shedule->language_code,
                'group_code' => $shedule->group_code,
                'date' => $shedule->date,
                'teacher' => $shedule->teacher,
                'author_id' => User::where('id', $shedule->author_id)->first()->name,
                'shedule' => $shedule,
            ])
    @endforeach

@endsection
