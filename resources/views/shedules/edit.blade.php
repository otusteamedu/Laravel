@php
    /**
     * @var int|null $sheduleId - The ID of the shedule (for edit form)
     * @var string|null $language_code - The language_code of the lesson
     * @var string|null $group_code - The group_code of the shedule
     * @var string|null $date - The date
     * @var string|null $teacher - The teacher
     */
@endphp

@extends ('layouts.main')

@section('title', isset($sheduleId) ? 'Редактировать' : 'Создать')

@section('content')
    <div class="container mx-auto px-4">
        <div style="text-align:left">
            <h3>Коды языковых групп:</h3>
            <p> 1 - английский</p>
            <p>2 - испанский</p>
            <p>3 - китайский</p>
            <h3>Коды возрастных групп:</h3>
            <p>1 - младшая, 5-9 лет</p>
            <p>2 - средняя, 9-12 лет</p>
            <p> 3 - старшая, 12-17 лет</p>
            <p></p>
        </div>

        <h1>
            {{ isset($sheduleId) ? 'Редактировать' : 'Создать'}}
        </h1>

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ isset($sheduleId) ? route('shedules.update', $sheduleId) : route('shedules.store') }}" method="POST">
            @csrf
            @if (isset($sheduleId))
                @method('PUT')
            @endif

            <div class="mb-4 editfield">
                <label for="language_code">Язык</label>
                <input type="text" name="language_code" id="language_code" value="{{ old('language_code', $language_code ?? '') }}"
                       class="{{$errors->has('language_code') ? 'invalid' : ''}}"
                       style="width: 1040px"
                       required>
            </div>

            <div class="mb-4 editfield">
                <label for="group_code">Группа</label>
                <input type="text" name="group_code" id="group_code" value="{{ old('group_code', $group_code ?? '') }}"
                       class="{{$errors->has('group_code') ? 'invalid' : ''}}"
                       style="width: 1040px"
                       required>
            </div>
            <div class="mb-4 editfield">
                <label for="date">Дата</label>
                <input type="text" name="date" id="date" value="{{ old('date', $date ?? '') }}"
                       class="{{$errors->has('date') ? 'invalid' : ''}}"
                       style="width: 1040px"
                       required>
            </div>
            <div class="mb-4 editfield">
                <label for="teacher">Преподаватель</label>
                <input type="text" name="teacher" id="teacher" value="{{ old('teacher', $teacher ?? '') }}"
                       class="{{$errors->has('teacher') ? 'invalid' : ''}}"
                       style="width: 1040px"
                       required>
            </div>

            <input type="hidden" name="author_id" id="author_id" value="{{ Auth::user()->id  }}" />

            <div>
                <button type="submit" class="">
                    {{ isset($sheduleId) ? 'Update' : 'Create' }}
                </button>
            </div>
        </form>
    </div>
@endsection
