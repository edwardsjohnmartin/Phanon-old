@extends('layouts.app')

@section('content')
    <h1>Create Lesson</h1>
    {!! Form::open(['id' => 'createLesson', 'action' => 'LessonsController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', \Carbon\Carbon::now()->toDateString())}}
            {{Form::time('open_time', \Carbon\Carbon::now()->toTimeString())}}
        </div>

        @if(count($exercises) > 0)
            <div class="form-group">
                <label>Select which exercises you want in the lesson</label>
                <select id="exercises" name="exercises[]" multiple class="form-control" onchange="updateExerciseList()">
                    @foreach($exercises as $exercise)
                        <option value="{{$exercise->id}}">{{$exercise->prompt}}</option>
                    @endforeach
                </select>
            </div>

            <div id="exerciseDiv">
                <label>Drag and drop the exercises to change the ordering of the exercises which exercises</label>
                <ol id="sortableExercises">
                </ol>
            </div>
        @else
            <p>No exercises exist</p>
        @endif

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
        updateExerciseList();
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
        $("#createLesson").submit(function(){
            var selectedExercises = document.getElementById("sortableExercises").getElementsByTagName("li");

            for(var i = 0; i < selectedExercises.length; i++){
                var exerciseInput = document.createElement("input");
                exerciseInput.setAttribute("type", "hidden");
                exerciseInput.setAttribute("name", "orderedExercises[]");
                exerciseInput.setAttribute("value", selectedExercises[i].id);

                document.getElementById("createLesson").appendChild(exerciseInput);
            }
        });
    </script>
@endsection