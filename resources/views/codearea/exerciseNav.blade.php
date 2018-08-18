@php 
    $exercise_count = 1;
    $found_current = false;
@endphp

<div id="exercisePanel">
    <ol id="exerciseList" data-url="{{url("/exercises/move")}}" data-lesson-id="{{$lesson_id}}">
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

            @component('codearea.exerciseNavItem', [
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
                <a href="#" tooltip="Append new Code Exercise to list."
                onclick="addNewExerciseToLesson('{{url('/ajax/exercisecreate')}}', 'addExercise');">+</a>
            </li>
            <li id="addChoiceExercise" class="exercise addNew mini active" data-item-count="{{$exercise_count}}">
                <a href="#" tooltip="Append new Choice Exercise to list."
                onclick="addNewExerciseToLesson('{{url('/ajax/exercisecreate')}}', 'addChoiceExercise');">+</a>
            </li>
            <li id="addScaleExercise" class="exercise addNew mini active" data-item-count="{{$exercise_count}}">
                <a href="#" tooltip="Append new Scale Exercise to list."
                onclick="addNewExerciseToLesson('{{url('/ajax/exercisecreate')}}', 'addScaleExercise');">+</a>
            </li>
        @endif
    </ol>
</div>

@section("scripts-end")
    @parent
    @if($role->hasPermissionTo(Permissions::EXERCISE_EDIT))
        <script>
            $("#exerciseList").sortable({
                cancel: "#addExercise",
                placeholder: "exercise mini",
                stop: function (evt, ui) {
                    var t = ui.item;
                    var p = t.prev();
                    var url = $("#exerciseList").attr("data-url");

                    var curr_id = t[0].id.split("_")[1];
                    var prev_id;
                    var hasPrevious = p.length > 0;
                    if (hasPrevious)
                        prev_id = p[0].id.split("_")[1];
                    else
                        prev_id = -1; // no previous; at start of list.

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            exercise_id: curr_id,
                            previous_id: prev_id,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            if (data.success) {
                                addPopup(data.message, "save");
                                // reorder numbers
                                var count;
                                if (hasPrevious) {
                                    count = parseInt(p.attr("data-item-count"));
                                    count++; // increment to set to this element
                                } else {
                                    count = 1; // start of list; start at 1
                                }
                                shiftExerciseNumbers(p, count);

                            } else {
                                addPopup(data.message, "error");
                            }
                        },
                        error: function (x, d, o, p) {
                            console.log(x);
                            console.log(d);
                            addPopup("Could not save exercise", "error");
                        }
                    });
                    

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
    @endif
@endsection
