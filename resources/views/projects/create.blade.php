@extends('layouts.app')

@section('content')
    <h1>Create Project</h1>
    {!! Form::open(['action' => 'ProjectsController@store', 'method' => 'POST']) !!}
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

        <div class="form-group">
            {{Form::label('prompt', 'Prompt')}}
            {{Form::textarea('prompt', '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('pre_code', 'Pre-Code')}}
            {{Form::textarea('pre_code', '', ['class' => 'form-control code'])}}
        </div>
        <div class="form-group">
            {{Form::label('start_code', 'Start Code')}}
            {{Form::textarea('start_code', '', ['class' => 'form-control code'])}}
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