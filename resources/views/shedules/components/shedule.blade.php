@use('App\Config')

    <div class="newscont">
         <div class="newscont__id">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $sheduleId }}</a>
        </div>
        <div class="newscont__column">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $language_code }} ({{ Config::LANG[$language_code] }})</a>
        </div>
                <div class="newscont__column">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $group_code }} ({{ Config::AGE[$group_code] }})</a>
        </div>
                <div class="newscont__column">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $date }}</a>
        </div>
                <div class="newscont__column">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $teacher }}</a>
        </div>
        <div class="newscont__column">
            {{ $date }}
        </div>
        <div class="newscont__column">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">Подробнее</a>
            <a class="" href="{{ route('shedules.edit', ['shedule' => $shedule]) }}">Редактировать</a>
        </div>
        <div class="newscont__column">
            {{ $author_id }}
        </div>
    </div>

