<?php
    //use App\Lesson;
    //$lesson = new Lesson();

    $numberFound = count($lesson->unorderedExercises);

    if($numberFound > 0){
        $stats = $lesson->CompletionStats(auth()->user()->id);
        $percComplete = $stats->PercComplete;
        $percComplete = floor($percComplete * 100);
        $stats_completed = $stats->Completed;
        $stats_exercise_count = $stats->ExerciseCount;
    } else {
        $stats = null;
        $percComplete = 0;
        $stats_completed = 0;
        $stats_exercise_count = 0;
    }
//@endphp
?>
<li id="lesson_{{$lesson->id}}" class="lesson {{$percComplete == 100?" completed":""}}">
    @if($role->hasPermissionTo(Permissions::LESSON_EDIT))
     <button class="edit" data-item-type="lesson" data-item-id="{{$lesson->id}}"
             >Edit</button>
    @endif
    <a href="{{url('/code/lesson/' . $lesson->id)}}">
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
