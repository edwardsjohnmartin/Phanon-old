@extends('layouts.app')

@section('content')
    <h1>Edit Lesson</h1>
    {!! Form::open(['id' => 'editLesson', 'action' => ['LessonsController@update', $lesson->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $lesson->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>

        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', new \Carbon\Carbon($lesson->open_date))}}
            {{Form::time('open_time', date("H:i:s", strtotime($lesson->open_date)))}}
        </div>

        @if(count($exercises) > 0)
            <div class="form-group">
                <label>Select which exercises you want in the lesson</label>
                <select id="exercises" name="exercises[]" multiple class="form-control" onchange="updateExerciseList()">
                    @foreach($exercises as $exercise)
                        <option value="{{$exercise->id}}" @if(in_array($exercise->id, $lesson_exercise_ids)) selected @endif>{{$exercise->prompt}}</option>
                    @endforeach
                </select>
            </div>

            <div id="exerciseDiv">
                <label>Drag and drop the exercises to change the ordering of the exercises which exercises</label>
                <ol id="sortableExercises">
                    @foreach($lesson_exercises as $l_exercise)
                        <li id="{{$l_exercise->id}}">{{$l_exercise->prompt}}</li>
                    @endforeach
                </ol>
            </div>
        @else
            <p>No exercises exist</p>
        @endif

        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
    
    <script>
        $(document).ready(function(){
            $('#exercises').multiselect({
                nonSelectedText: 'Select Exercise',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });
    </script>

    <script>
        function updateExerciseList()
        {
            var exerciseList = document.getElementById("sortableExercises");

            // Remove all options from the sortable list
            exerciseList.innerHTML = "";

            // Get all selected options from multiselect element
            var exerciseOptions = document.getElementById("exercises").options;

            var exerciseIDs = [];
            var exercisePrompts = [];

            for(var i = 0; i < exerciseOptions.length; i++){
                if(exerciseOptions[i].selected){
                    exerciseIDs.push(exerciseOptions[i].value);
                    exercisePrompts.push(exerciseOptions[i].innerHTML);
                }
            }

            // Add all selected options from multiselect to the sortable list
            for(var i = 0; i < exerciseIDs.length; i++){
                var exerciseLI = document.createElement("li");

                exerciseLI.appendChild(document.createTextNode(exercisePrompts[i]));
                exerciseLI.setAttribute("id", exerciseIDs[i]);

                exerciseList.appendChild(exerciseLI);
            }
        }
    </script>

    <script>
        // Use jquery to make the table sortable by dragging and dropping
        $("#sortableExercises").sortable({
            axis: "y",
            containment: "#exerciseDiv",
            scroll: false
        });
        $("#sortableExercises").disableSelection();

        // Create hidden form inputs to pass the exercise_order with the rest of the data
        $("#editLesson").submit(function(){
            var selectedExercises = document.getElementById("sortableExercises").getElementsByTagName("li");

            for(var i = 0; i < selectedExercises.length; i++){
                var exerciseInput = document.createElement("input");
                exerciseInput.setAttribute("type", "hidden");
                exerciseInput.setAttribute("name", "orderedExercises[]");
                exerciseInput.setAttribute("value", selectedExercises[i].id);

                document.getElementById("editLesson").appendChild(exerciseInput);
            }
        });
    </script>
@endsection