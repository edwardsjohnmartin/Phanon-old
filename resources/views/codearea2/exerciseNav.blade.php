@php 
    $exercise_count = 1;
    $found_current = false;
@endphp

<div id="exercisePanel">
    <ol id="exerciseList">
        @foreach($exercises as $exercise)
            @php
                $class = "";
                if($exercise->id == $current_exercise_id){
                    $class .= "current ";
                }
                if($exercise->getProgressForUser()->completed()){
                    $class .= "active completed ";
                } else {
                    if(!$found_current){
                        $class .= "active ";
                        $found_current = true;
                    } else {
                        $class .= "inactive ";
                    }
                }
            @endphp
            <li class="exercise mini {{$class}}">
                @if($class != "inactive ")
                <a href="{{url('newexercise/' . $exercise->id)}}">
                    @php
                        echo $exercise_count++;
                    @endphp
                    <span class="lessonCode"></span>
                </a>
                @endif
            </li>
        @endforeach
    </ol>
</div>