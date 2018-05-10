@extends('layouts.app')

@section('content')
    <a href="{{url('/lessons')}}" class="btn btn-default">Go Back</a>
    <h1>{{$lesson->name}}</h1>
    <h2>Open Date: {{date_format(DateTime::createFromFormat('Y-m-d G:i:s', $lesson->open_date), 'm/d/Y h:i a')}}</h2>
    <hr>
    @if(!Auth::guest())
        <a href="{{url('/lessons/' . $lesson->id . '/edit')}}" class="btn btn-default">Edit</a>

        {!!Form::open(['action' => ['LessonsController@destroy', $lesson->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close() !!}
    @endif
@endsection