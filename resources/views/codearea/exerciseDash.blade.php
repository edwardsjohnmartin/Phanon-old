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
             $exerciseCounter = 0;
@endphp
<ol id="exerciseList">
    @foreach($lessons as $lesson)
        @foreach($lesson->exercises() as $exercise)
    <?php $exerciseCounter++ ?>
    <li class="exercise mini" data-lesson-id="{{$lesson->id}}">
        <a href="{{url($path ,['id' => $lesson->module_id,
                             'eid'=>$exercise->id])}}" onclick="showExcercise({{$exercise->id}})">
                {{$exerciseCounter}}
        </a>
        <span class="lessonCode">{{$lesson->id}}</span>

    </li>
    @endforeach
    @endforeach
</ol>
