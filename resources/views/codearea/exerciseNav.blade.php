@php 
    $exercise_count = 1;
    $found_current = false;
@endphp

<div id="exercisePanel">
    <ol id="exerciseList">
        @foreach($exercises as $exercise)
            @php
                $is_active = true;
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
                        $is_active = false;
                    }
                }
            @endphp

            <li class="exercise mini {{$class}}">
                @if($is_active) 
                    <a href="{{url('code/exercise/' . $exercise->id)}}">{{$exercise_count++}}</a>
                @endif
            </li>
        @endforeach
    </ol>
</div>
