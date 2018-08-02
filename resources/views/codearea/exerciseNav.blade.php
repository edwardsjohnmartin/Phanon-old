@php 
    $exercise_count = 1;
    $found_current = false;
@endphp

<div id="exercisePanel">
    <ol id="exerciseList" data-lesson-id="{{$lesson_id}}">
        @foreach($exercises as $exercise)
            @php
                // Check each exercise
                $is_active = true;
                $class = "";

                if($exercise->id == $current_exercise_id){
                    $class .= "current ";
                }
                
                if($exercise->getProgressForUser()->completed()){
                    $class .= "active completed ";
                } else {
                    // not completed
                    if(!$found_current || (isset($role) && $role->hasPermissionTo(Permissions::EXERCISE_EDIT))){
                        // this should be just he first exercise after all completed.
                        $class .= "active ";
                        $found_current = true;
                    } else {
                        $class .= "inactive "; // these are locked
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
        <li id="addExercise" class="exercise addNew mini active" data-item-count="{{$exercise_count}}">
            <a href="#" 
               onclick="addNewExerciseToLesson('{{url('/ajax/exercisecreate')}}','addExercise');return false;">+</a>
        </li>
        @endif
    </ol>
</div>
