@extends('layouts.app')

@section('content')
    <h1>Clone Exercise</h1>
    <label>Original Author</label>
    <p>{{$exercise->user->name}}</p>
    {!! Form::open(['action' => ['ExercisesController@create_clone'], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('prompt', 'Prompt')}}
            {{Form::textarea('prompt', $exercise->prompt, ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('pre_code', 'Pre-Code')}}
            {{Form::textarea('pre_code', $exercise->pre_code, ['class' => 'form-control code'])}}
        </div>
        <div class="form-group">
            {{Form::label('start_code', 'Start Code')}}
            {{Form::textarea('start_code', $exercise->start_code, ['class' => 'form-control code'])}}
        </div>
        <div class="form-group">
            {{Form::label('test_code', 'Test Code')}}
            {{Form::textarea('test_code', $exercise->test_code, ['class' => 'form-control code'])}}
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