@php
    //use App\Lesson;
    //$lesson = new Lesson();
    if(count($lesson->exercises()) > 0){
        $stats = $lesson->CompletionStats(auth()->user()->id);
        $percComplete = $stats->PercComplete;
        $percComplete = floor($percComplete * 100);
        $next_incomplete_exercise = $lesson->nextIncompleteExercise();
        $next_incomplete_exercise_id = $next_incomplete_exercise->id;
        $stats_completed = $stats->Completed;
        $stats_exercise_count = $stats->ExerciseCount;
    } else {
        $stats = null;
        $percComplete = 0;
        $next_incomplete_exercise = null;
        $next_incomplete_exercise_id = 0;
        $stats_completed = 0;
        $stats_exercise_count = 0;
    }
@endphp
<li class="lesson sortable">
    <a href="{{url('/code/exercise/' . $next_incomplete_exercise_id)}}">
        {{--
        <span>Lesson </span>
        <span class="itemCount"></span> --}}

        <div class="completion p{{$percComplete}}">
            <span>
                @if($stats_completed < $stats_exercise_count)
                {{$stats_completed}}/{{$stats_exercise_count}}
            @else
                Done
                @endif
            </span>
            <div class="slice">
                <div class="bar"></div>
                <div class="fill"></div>
            </div>
        </div>
        <span class="name">{{$lesson->name}}</span>
    </a>
</li>