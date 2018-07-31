<?php
    //use App\Lesson;
    //$lesson = new Lesson();
$numberFound = 0;
if ($eagered){
    $numberFound = count($lesson->unorderedExercises());
}else{
    $numberFound = count($lesson->exercises());
}
    if($numberFound > 0){
        $stats = $lesson->CompletionStats(auth()->user()->id);
        $percComplete = $stats->PercComplete;
        $percComplete = floor($percComplete * 100);
        //if ($eagered){
        //    $next_incomplete_exercise_id = $lesson->nextExerciseToDo($progress,auth()->user()->id)->id;
        //}else{
        //    $next_incomplete_exercise_id = $lesson->nextIncompleteExercise()->id;
        //}
        $next_incomplete_exercise_id = 0;
        $stats_completed = $stats->Completed;
        $stats_exercise_count = $stats->ExerciseCount;
    } else {
        $stats = null;
        $percComplete = 0;
        $next_incomplete_exercise_id = 0;
        $stats_completed = 0;
        $stats_exercise_count = 0;
    }
//@endphp
?>
<li class="lesson sortable{{$percComplete == 100?" completed":""}}">
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
