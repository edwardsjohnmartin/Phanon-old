@php
    $stats = $lesson->CompletionStats(auth()->user()->id);
    if(!is_null($stats)){
        $exercisesCompleted = $stats->Completed;
        $exerciseCount = $stats->ExerciseCount;
        $percentCompleted = $stats->PercComplete * 100;
    } else {
        $exercisesCompleted = 0;
        $exerciseCount = 0;
        $percentCompleted = 0;
    }
@endphp
<li class="lesson sortable">
    <a href="{{url('/code/exercise/' . $lesson->nextIncompleteExercise()->id)}}" >
        <div class="completion p{{$percentCompleted}}">
            <span>
                @if($percentCompleted == 100)
                    Done
                @else
                    {{$exercisesCompleted}}/{{$exerciseCount}}
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
