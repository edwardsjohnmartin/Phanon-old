@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/create-edit-form')
    @endcomponent
@endsection

@section('content')
    <h1>Create Lesson</h1>
    {!! Form::open(['id' => 'createLesson', 'action' => 'LessonsController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>

        @if(count($exercises) > 0)
            <div class="form-group">
                <label>Select which exercises you want in the lesson</label>
                <select id="exercises" name="exercises[]" multiple class="form-control" onchange="updateList('sortableExercises', 'exercises')">
                    @foreach($exercises as $exercise)
                        <option value="{{$exercise->id}}">{{$exercise->prompt}}</option>
                    @endforeach
                </select>
            </div>

            <div id="exerciseDiv">
                <label>Drag and drop the exercises to change the ordering they will appear in the lesson</label>
                <ol id="sortableExercises">
                </ol>
            </div>
        @else
            <p>No exercises exist</p>
        @endif

        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        makeMultiSelect('exercises', 'Select Exercises');

        // Use jquery to make the table sortable by dragging and dropping
        $("#sortableExercises").sortable({
            axis: "y",
            containment: "#exerciseDiv",
            scroll: false
        });
        $("#sortableExercises").disableSelection();

        addInputsToForm("createLesson", "sortableExercises", "exercise_order");
    </script>
@endsection