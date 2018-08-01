@php 
    $exercise_count = 1;
    $found_current = false;
@endphp

<div id="exercisePanel">
    <ol id="exerciseList" data-lesson-id="{{$lesson_id}}">
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
        @component('codearea.exerciseNavItem',[
        'exercise' => $exercise,
        'exercise_count' => $exercise_count++,
        'is_active' => $is_active,
        'class' => $class
        ])
        @endcomponent
        @endforeach
    @if($role->hasPermissionTo(Permissions::EXERCISE_EDIT))
        <li class="exercise addNew mini active">
            <a id="addExercise" href="#" data-count="{{$exercise_count}}"
               onclick="addNewExerciseToLesson('{{url('/ajax/exercisecreate')}}');return false;">+</a>
        </li>
        @endif
    </ol>
</div>
