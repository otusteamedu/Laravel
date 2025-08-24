

    <div class="newscont">
         <div class="newscont__id">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $sheduleId }}</a>
        </div>
        <div class="newscont__title">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $language }}</a>
        </div>
                <div class="newscont__title">
            <a class="" href="{{ route('shedules.show', ['shedule' => $sheduleId]) }}">{{ $group }}</a>
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

