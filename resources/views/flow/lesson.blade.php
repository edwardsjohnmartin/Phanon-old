@php
             //use App\Lesson;
             //$lesson = new Lesson();
             $lessonStats = $lesson->CompletionStats(auth()->user()->id);
             $percComplete = $lessonStats->PercComplete;
             $percComplete = floor($percComplete * 100);
             @endphp
<li class="lesson">
    <a href="{{url('/code/exercise/' . $lesson->nextIncompleteExercise()->id)}}">
        {{--
        <span>Lesson </span>
        <span class="itemCount"></span> --}}

        <div class="completion p{{$percComplete}}">
            <span>
                @if($lessonStats->Completed < $lessonStats->ExerciseCount)
                {{$lessonStats->Completed}}/{{$lessonStats->ExerciseCount}}
            @else
                Done
                @endif
            </span>
            <div class="slice">
                <div class="bar"></div>
                <div class="fill"></div>
            </div>
        </div>
        <p>{{$lesson->name}}</p>
    </a>
</li>