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
@endphp
<ol id="exerciseList">
    @foreach($lessons as $lesson)
          @foreach($lesson->exercises() as $exercise)
            @php
             $exerciseCounter++;
             $li_class = "";
             $exercise_completed = false;
             //HACK: This is only done like this here to prevent the code/review page from breaking from $module_completion not being defined

             if(!empty($module_completion)){
                 $exercise_completed = $module_completion[$lesson->id][$exercise->id] == 1;
             }

             if($exercise_completed){
                     $li_class = "completed";
                 }

            @endphp
    <li class="exercise mini {{$li_class}}{{ $exercise->id == $currentExerciseID?" current":""}}"
        data-lesson-id="{{$lesson->id}}">
        <a href="{{url($path ,['id' => $lesson->module_id,
                         'eid'=>$exercise->id])}}"
            onclick="showExcercise({{$exercise->id}})">
            {{$exerciseCounter}}
        </a>
        <span class="lessonCode">{{$lesson->id}}</span>

    </li>
        @endforeach
    @endforeach
</ol>
