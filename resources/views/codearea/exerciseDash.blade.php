@php
             if(!isset($isReview)){
                 $path = "code";
             }else{
                 $path = "code/review";
             }
@endphp
@section('scripts')
    @parent
<script>
    function showExcercise(num) {
        return false; // cancel click event.
    }
</script>
@endsection
@php
             $appPath = Route::current()->parameters["eid"];
             $exerciseCounter = 0;
             $currentExerciseFound = false;
             $currentExerciseID = (int)$appPath;
             $canBeDone = true; // always only one more than the last completed exercise.
            $isClickable = true;
            $isClickableCount = 1; // number of links after completed that are clickable.
            $exerciseOrderArray = []; // new array for IDs
@endphp
<ol id="exerciseList">
    @foreach($lessons as $lesson)
          @foreach($lesson->exercises() as $exercise)

    @php
    $exerciseOrderArray[] = $exercise->id;
    $exerciseCounter++;
    $li_class = "";
    $isCompleted = false;
    //HACK: This is only done like this here to prevent the code/review page from breaking from $module_completion not being defined
    if(!empty($module_completion)){
        $isCompleted = $module_completion[$lesson->id][$exercise->id] == 1;
    }

    $isCurrent = $exercise->id == $currentExerciseID;

    if($isCompleted){
        $li_class = "completed";
    }else{
        // if still have clickable link left, skip flag
        if($isClickableCount <= 0){
            $isClickable = false; // should only hit this after the last completed exercise.
        }
        // reduce the number of clickable links by one
        $isClickableCount--;
    }

    @endphp
    <li class="exercise mini {{$li_class}}{{$isClickable? " active":" inactive"}}{{$isCurrent ?" current":""}}"
        data-lesson-id="{{$lesson->id}}">
        @if($isClickable)
        <a href="{{url($path ,['id' => $lesson->module_id,
                         'eid'=>$exercise->id])}}"
            onclick="showExcercise({{$exercise->id}})">
            {{$exerciseCounter}}
        </a>
        @else
        <span>
            {{$exerciseCounter}}
        </span>
        @endif
        <span class="lessonCode">{{$lesson->id}}</span>

    </li>
    @endforeach
    @endforeach
</ol>
