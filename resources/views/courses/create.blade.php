@extends('layouts.app')

@section('scripts')
    @component('scriptbundles/create-edit-form')
    @endcomponent
@endsection

@section('content')
    <h1>Create Course</h1>
    {!! Form::open(['id' => 'createCourse', 'action' => 'CoursesController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', \Carbon\Carbon::now()->toDateString())}}
            {{Form::time('open_time', \Carbon\Carbon::now()->toTimeString())}}
        </div>
        <div class="form-group">
            {{Form::label('close_date', 'Close Date')}}
            {{Form::date('close_date', \Carbon\Carbon::now()->toDateString())}}
            {{Form::time('close_time', \Carbon\Carbon::now()->toTimeString())}}
        </div>

        @if(count($concepts) > 0)
            <div class="form-group">
                <label>Select which concepts you want in the course</label>
                <select id="concepts" name="concepts[]" multiple class="form-control" onchange="updateList('sortableConcepts', 'concepts')">
                    @foreach($concepts as $concept)
                        <option value="{{$concept->id}}">{{$concept->name}}</option>
                    @endforeach
                </select>
            </div>

            <div id="conceptDiv">
                <label>Drag and drop the concepts to change the ordering they will appear in the course</label>
                <ol id="sortableConcepts">
                </ol>
            </div>
        @else
            <p>No concepts exist</p>
        @endif

        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        $(document).ready(function(){
            $('#concepts').multiselect({
                nonSelectedText: 'Select Concepts',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });
    </script>

    <script>
        // Use jquery to make the table sortable by dragging and dropping
        $("#sortableConcepts").sortable({
            axis: "y",
            containment: "#conceptDiv",
            scroll: false
        });
        $("#sortableConcepts").disableSelection();

        addInputsToForm("createCourse", "sortableConcepts", "concept_order");
    </script>
@endsection