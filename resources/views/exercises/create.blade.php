@extends('layouts.app')

@section('content')
    <h1>Create Exercise</h1>
    {!! Form::open(['action' => 'ExercisesController@store', 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('prompt', 'Prompt')}}
            {{Form::textarea('prompt', '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('pre_code', 'Pre-Code')}}
            {{Form::textarea('pre_code', '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('start_code', 'Start Code')}}
            {{Form::textarea('start_code', '', ['class' => 'form-control'])}}
        </div>
        <div class="form-group">
            {{Form::label('test_code', 'Test Code')}}
            {{Form::textarea('test_code', '', ['class' => 'form-control'])}}
        </div>
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection