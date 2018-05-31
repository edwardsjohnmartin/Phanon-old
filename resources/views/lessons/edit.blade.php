@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/create-edit-form')
    @endcomponent
@endsection

@section('content')
    <h1>Edit Lesson</h1>
    {!! Form::open(['id' => 'editLesson', 'action' => ['LessonsController@update', $lesson->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $lesson->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>

        @if(count($exercises) > 0)
            <div class="form-group">
                <label>Select which exercises you want in the lesson</label>
                <select id="exercises" name="exercises[]" multiple class="form-control" onchange="updateList('sortableExercises', 'exercises')">
                    @foreach($exercises as $exercise)
                        <option value="{{$exercise->id}}" @if(in_array($exercise->id, $lesson_exercise_ids)) selected @endif>{{$exercise->prompt}}</option>
                    @endforeach
                </select>
            </div>

            <div id="exerciseDiv">
                <label>Drag and drop the exercises to change the ordering of the exercises which exercises</label>
                <ol id="sortableExercises">
                    @foreach($lesson_exercises as $exercise)
                        <li id="{{$exercise->id}}">{{$exercise->prompt}}</li>
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
        // Use jquery to make the table sortable by dragging and dropping
        $("#sortableExercises").sortable({
            axis: "y",
            containment: "#exerciseDiv",
            scroll: false
        });
        $("#sortableExercises").disableSelection();

        addInputsToForm("editLesson", "sortableExercises", "exercise_order");
    </script>
@endsection