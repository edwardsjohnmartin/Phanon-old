@extends('layouts.app')

@section('content')
    <h1>Clone Module</h1>
    <label>Original Author</label>
    <p>{{$module->owner->name}}</p>
    {!! Form::open(['action' => ['ModulesController@createClone'], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $module->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', new \Carbon\Carbon($module->open_date))}}
            {{Form::time('open_time', date("H:i:s", strtotime($module->open_date)))}}
        </div>
        <div class="form-group">
            {{Form::label('close_date', 'Close Date')}}
            {{Form::date('close_date', new \Carbon\Carbon($module->close_date))}}
            {{Form::time('close_time', date("H:i:s", strtotime($module->close_date)))}}
        </div>

        @if(count($lessons) > 0)
            <div class="form-group">
                <label>Select which lessons you want in the module</label>
                <select id="lessons" name="lessons[]" multiple class="form-control">
                    @foreach($lessons as $lesson)
                        <option value="{{$lesson->id}}" @if(in_array($lesson->id, $module_lesson_ids)) Selected @endif>{{$lesson->name}}</option>
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
                        <option value="{{$project->id}}" @if(in_array($project->id, $module_project_ids)) Selected @endif>{{$project->name}}</option>
                    @endforeach
                </select>
            </div>
        @else
            <p>No projects exist</p>
        @endif
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script>
        makeMultiSelect('lessons', 'Select Lessons');

        makeMultiSelect('projects', 'Select Projects');
    </script>
@endsection