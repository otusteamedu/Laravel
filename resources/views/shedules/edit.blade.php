@php
    /**
     * @var int|null $sheduleId - The ID of the shedule (for edit form)
     * @var string|null $language - The language of the lesson
     * @var string|null $group - The group of the shedule
     * @var string|null $date - The date
     * @var string|null $teacher - The teacher
     */
@endphp

@extends ('layouts.main')

@section('title', isset($sheduleId) ? 'Редактировать' : 'Создать')

@section('content')
    <div class="container mx-auto px-4">
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
                <label for="language">Язык</label>
                <input type="text" name="language" id="language" value="{{ old('language', $language ?? '') }}"
                       class="{{$errors->has('language') ? 'invalid' : ''}}"
                       style="width: 1040px"
                       required>
            </div>

            <div class="mb-4 editfield">
                <label for="group">Группа</label>
                <input type="text" name="group" id="group" value="{{ old('group', $group ?? '') }}"
                       class="{{$errors->has('group') ? 'invalid' : ''}}"
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
