@php 
    $exercise_count = 1;
    $found_current = false;
@endphp

<div id="exercisePanel">
    <ol id="exerciseList" data-url="{{url("/ajax/exercisemove")}}" data-lesson-id="{{$lesson_id}}">
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
        'class' => $class,
        'role' => $role
        ])
        @endcomponent
        @endforeach
    @if($role->hasPermissionTo(Permissions::EXERCISE_EDIT))
        <li id="addExercise" class="exercise addNew mini active" data-item-count="{{$exercise_count}}">
            <a href="#" tooltip="Append new Exercise to list."
               onclick="addNewExerciseToLesson('{{url('/ajax/exercisecreate')}}','addExercise');return false;">+</a>
        </li>
        @endif
    </ol>
</div>
@section("scripts-end")
@parent
<script>
    $("#exerciseList").sortable({
        cancel: "#addExercise",
        placeholder: "exercise mini",
        stop: function (evt, ui) {
            var t = ui.item;
            var p = t.prev();
            var n = t.next();

            
            

        }
    })
    $("#exerciseList").mouseover(function (e) {
        e = e || window.event;
        var tar = e.target || e.srcElement;
        var content = tar.getAttribute("data-prompt");
        if (content != undefined && content != "") {
            displayMessage(content,e.clientX,e.clientY);
        }
    });
</script>
@endsection
