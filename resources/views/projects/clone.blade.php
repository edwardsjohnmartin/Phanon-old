@extends('layouts.app')

@section('content')
    <h1>Edit Project</h1>
    <label>Original Author</label>
    <p>{{$project->user->name}}</p>
    {!! Form::open(['action' => ['ProjectsController@createClone'], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $project->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', new \Carbon\Carbon($project->open_date))}}
            {{Form::time('open_time', date("H:i:s", strtotime($project->open_date)))}}
        </div>
        <div class="form-group">
            {{Form::label('close_date', 'Close Date')}}
            {{Form::date('close_date', new \Carbon\Carbon($project->close_date))}}
            {{Form::time('close_time', date("H:i:s", strtotime($project->close_date)))}}
        </div>
        <div class="form-group">
            {{Form::label('prompt', 'Prompt')}}
            {{Form::textarea('prompt', $project->prompt, ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('pre_code', 'Pre-Code')}}
            {{Form::textarea('pre_code', $project->pre_code, ['class' => 'form-control code'])}}
        </div>
        <div class="form-group">
            {{Form::label('start_code', 'Start Code')}}
            {{Form::textarea('start_code', $project->start_code, ['class' => 'form-control code'])}}
        </div>
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    <script type="text/javascript">
        makeClassCodeMirror(".code").forEach(function (editorEl){
            CodeMirror.fromTextArea(editorEl, {
                lineNumbers: true,
                cursorBlinkRate: 0,
                autoCloseBrackets: true,
                tabSize: 4,
                indentUnit: 4,
                matchBrackets: true 
            });
        });
    </script>
@endsection