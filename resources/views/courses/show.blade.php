@extends('layouts.app')

@section('content')
    <a href="/courses" class="btn btn-default">Go Back</a>
    <h1>{{$course->name}}</h1>
    <small>Created on {{$course->created_at}} by {{$course->user->name}}</small>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $course->user_id)
            <a href="/courses/{{$course->id}}/edit" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['CoursesController@destroy', $course->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection