

@php
$language = [
    1 => 'английский',
    2 => 'испанский',
    3 => 'китайский'
];

$group = [
    1 => 'младшая, 5-9 лет',
    2 => 'средняя, 9-12 лет',
    3 => 'старшая, 12-17 лет'
];

@endphp

    <div class="newscont">
         <div class="newscont__id">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $sheduleId }}</a>
        </div>
        <div class="newscont__title">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $language_code }} ({{ $language[$language_code] }})</a>
        </div>
                <div class="newscont__title">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $group_code }} ({{ $group[$group_code] }})</a>
        </div>
                <div class="newscont__title">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $date }}</a>
        </div>
                <div class="newscont__title">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $teacher }}</a>
        </div>
        <div class="newscont__date">
            {{ $date }}
        </div>
        <div class="newscont__action">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">Read more</a>
            <a class="" href="{{ route('shedules.edit', ['shedule' => $shedule]) }}">Edit</a>
        </div>
        <div class="newscont__author">
            {{ $author_id }}
        </div>
    </div>

