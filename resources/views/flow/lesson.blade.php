@php
             //use App\Lesson;
             //$lesson = new Lesson();
             $stats = $lesson->CompletionStats(auth()->user()->id);
             $percComplete = $stats->PercComplete;
             $percComplete = floor($percComplete * 100);
             @endphp
<li class="lesson sortable">
    <a href="{{url('/code/exercise/' . $lesson->nextIncompleteExercise()->id)}}">
        {{--
        <span>Lesson </span>
        <span class="itemCount"></span> --}}

        <div class="completion p{{$percComplete}}">
            <span>
                @if($stats->Completed < $stats->ExerciseCount)
                {{$stats->Completed}}/{{$stats->ExerciseCount}}
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