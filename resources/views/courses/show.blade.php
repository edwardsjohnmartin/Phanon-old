@extends('layouts.app')

@section('content')
    <a href="{{url('/courses')}}" class="btn btn-default">Go Back</a>
    <a href="{{url('/courses/' . $course->id . '/fullview')}}" class="btn btn-default">Full View</a>
    <h1>{{$course->name}}</h1>
    <div>
        <label>Modules</label>
        @if(count($modules) > 0)
            <ul class="list-group">
            @foreach($modules as $module)
                <a href="{{url('/modules/' . $module->id)}}"><li class="list-group-item">{{$module->name}}</li></a>
            @endforeach
            </ul>
        @else
            <p>This course does not contain any modules</p>
        @endif
    </div>
    <div>
        <small>Author: {{$course->user->name}}</small>
    </div>
    <div>
        <small>Created On: {{$course->created_at}}</small>
    </div>
    <div>
        <small>Last Updated At: {{$course->updated_at}}</small>
    </div>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $course->user_id)
            <a href="{{url('/courses/' . $course->id . '/edit')}}" class="btn btn-default">Edit</a>

            {!!Form::open(['action' => ['CoursesController@destroy', $course->id], 'method' => 'POST' , 'class' => 'pull-right'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
            {!!Form::close() !!}
        @endif
    @endif
@endsection