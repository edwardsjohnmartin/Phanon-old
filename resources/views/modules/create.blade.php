@extends('layouts.app')

@section('content')
    <h1>Create Module</h1>
    {!! Form::open(['action' => 'ModulesController@store', 'method' => 'POST']) !!}
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

        @if(count($lessons) > 0)
            <div class="form-group">
                <label>Select which lessons you want in the module</label>
                <select id="lessons" name="lessons[]" multiple class="form-control">
                    @foreach($lessons as $lesson)
                        <option value="{{$lesson->id}}">{{$lesson->name}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p>No lessons exist</p>
        @endif

        @if(count($projects) > 0)
            <div class="form-group">
                <label>Select which projects you want in the module</label>
                <select id="projects" name="projects[]" multiple class="form-control">
                    @foreach($projects as $project)
                        <option value="{{$project->id}}">{{$project->name}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p>No projects exist</p>
        @endif

        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        $(document).ready(function(){
            $('#lessons').multiselect({
                nonSelectedText: 'Select Lesson',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });

        $(document).ready(function(){
            $('#projects').multiselect({
                nonSelectedText: 'Select Project',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });
        });
    </script>
@endsection