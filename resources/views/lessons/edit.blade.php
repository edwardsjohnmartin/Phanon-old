@extends('layouts.app')

@section('content')
    <h1>Edit Lesson</h1>
    {!! Form::open(['action' => ['LessonsController@update', $lesson->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $lesson->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        <div class="form-group">
            {{Form::label('open_date', 'Open Date')}}
            {{Form::date('open_date', new \Carbon\Carbon($module->open_date))}}
            {{Form::time('open_time', date("H:i:s", strtotime($lesson->open_date)))}}
        </div>
        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection