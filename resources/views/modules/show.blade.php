@extends('layouts.app')

@section('content')
    <a href="{{url('/modules')}}" class="btn btn-default">Go Back</a>
    <h1>{{$module->name}}</h1>
    <div>
        <label>Open Date</label>
        <p>{{date_format(DateTime::createFromFormat('Y-m-d G:i:s', $module->open_date), 'm/d/Y h:i a')}}</p>
    </div>
    <div>
        <label>Close Date</label>
        <p>{{date_format(DateTime::createFromFormat('Y-m-d G:i:s', $module->close_date), 'm/d/Y h:i a')}}</p>
    </div>
    <div>
        <label>Lessons</label>
        @if(count($lessons) > 0)
            <ul class="list-group">
            @foreach($lessons as $lesson)
                <a href="{{url('/lessons/' . $lesson->id)}}"><li class="list-group-item">{{$lesson->name}}</li></a>
            @endforeach
            </ul>
        @else
            <p>This module does not contain any lessons</p>
        @endif
    </div>
    <div class="well">
        @if(count($module->courses) > 0)
            <p>This module is contained in the following courses</p>
            <ul class="list-group">
            @foreach($module->courses as $course)
                <a href="{{url('/courses/' . $course->id)}}"><li class="list-group-item">{{$course->name}}</li></a>
            @endforeach
            </ul>
        @else
            <p>This module is not contained in any courses</p>
        @endif
    </div>
    <small>Created on {{$module->created_at}}</small>
    <hr>
    @if(!Auth::guest())
        <a href="{{url('/modules/' . $module->id . '/edit')}}" class="btn btn-default">Edit</a>

        {!!Form::open(['action' => ['ModulesController@destroy', $module->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
            {{Form::hidden('_method', 'DELETE')}}
            {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close() !!}
    @endif
@endsection