@extends('layouts.app')

@section('content')
    <h1>Edit Course</h1>
    {!! Form::open(['action' => ['CoursesController@update', $course->id], 'method' => 'POST']) !!}
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', $course->name, ['class' => 'form-control', 'placeholder' => 'Name'])}}
        </div>
        {{FORM::hidden('_method', 'PUT')}}
        {{Form::submit('Submit', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection