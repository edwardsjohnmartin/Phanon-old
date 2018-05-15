@extends('layouts.app')

@section('content')
    <a href="{{url('/modules')}}" class="btn btn-default">Go Back</a>
    <h1>{{$module->name}}</h1>
    <div>
        <label>Course</label>
        @if(!is_null($module->course))
            <p>{{$module->course->name}}</p>
        @else
            <p>Not contained in a course</p>
        @endif
    </div>
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
    <div>
        <label>Projects</label>
        @if(count($module->projects) > 0)
            <ul class="list-group">
            @foreach($module->projects as $project)
                <a href="{{url('/projects/' . $project->id)}}"><li class="list-group-item">{{$project->name}}</li></a>
            @endforeach
            </ul>
        @else
            <p>This module does not contain any projects</p>
        @endif
    </div>
    <div>
        <small>Author: {{$module->user->name}}</small>
    </div>
    <div>
        <small>Created On: {{$module->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$module->updated_at}}</small>
    </div>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $module->user_id)
            <a href="{{url('/modules/' . $module->id . '/edit')}}" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['ModulesController@destroy', $module->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection